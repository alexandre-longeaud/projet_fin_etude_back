<?php

namespace App\Entity;

use App\Repository\PictureOfTheWeekRepository;
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
}
