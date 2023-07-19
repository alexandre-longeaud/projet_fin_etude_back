<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 */
class Picture 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"picture","prompt","delete"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=500)
     * @Groups({"picture","prompt","add-picture"})
     * @Assert\NotBlank
     */
    private $prompt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"picture","prompt"})
     */
    private $nbClick;

    /**
     * @ORM\Column(type="date")
     * @Groups({"picture"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"picture","delete"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="picture",cascade={"persist", "remove"})
     * @Groups({"picture","prompt","add-review"})
     */
    private $reviews;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="picture",cascade={"persist", "remove"})
     * @Groups({"picture","prompt","add-picture"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Ia::class, inversedBy="pictures",cascade={"persist", "remove"})
     * @Groups({"picture","prompt","add-picture"})
     */
    private $ia;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="pictures")
     * @Groups({"picture"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=PictureOfTheWeek::class, inversedBy="picture")
     */
    private $pictureOfTheWeek;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="picture",cascade={"persist", "remove"})
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"picture"})
     */
    private $fileName;



    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->prompt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): self
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getNbClick(): ?int
    {
        return $this->nbClick;
    }

    public function setNbClick(?int $nbClick): self
    {
        $this->nbClick = $nbClick;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setPicture($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getPicture() === $this) {
                $review->setPicture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addPicture($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePicture($this);
        }

        return $this;
    }

    public function getIa(): ?Ia
    {
        return $this->ia;
    }

    public function setIa(?Ia $ia): self
    {
        $this->ia = $ia;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPictureOfTheWeek(): ?PictureOfTheWeek
    {
        return $this->pictureOfTheWeek;
    }

    public function setPictureOfTheWeek(?PictureOfTheWeek $pictureOfTheWeek): self
    {
        $this->pictureOfTheWeek = $pictureOfTheWeek;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setPicture($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPicture() === $this) {
                $like->setPicture(null);
            }
        }

        return $this;
    }

    /**
     * Vérifie si un utilisateur à mit un like.
     *
     * @param User $user
     * @return bool
     */
    public function isLikedByUser(User $user): bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    public function findLikeByUser(User $user): ?Like
{
    foreach ($this->likes as $like) {
        if ($like->getUser() === $user) {
            return $like;
        }
    }

    return null;
}

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

}
