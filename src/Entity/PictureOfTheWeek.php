<?php

namespace App\Entity;

use App\Repository\PictureOfTheWeekRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PictureOfTheWeekRepository::class)
 */
class PictureOfTheWeek
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeStampWeek;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="pictureOfTheWeek")
     */
    private $picture;

    public function __construct()
    {
        $this->picture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimeStampWeek(): ?\DateTimeInterface
    {
        return $this->timeStampWeek;
    }

    public function setTimeStampWeek(\DateTimeInterface $timeStampWeek): self
    {
        $this->timeStampWeek = $timeStampWeek;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPicture(): Collection
    {
        return $this->picture;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->picture->contains($picture)) {
            $this->picture[] = $picture;
            $picture->setPictureOfTheWeek($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->picture->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getPictureOfTheWeek() === $this) {
                $picture->setPictureOfTheWeek(null);
            }
        }

        return $this;
    }
}
