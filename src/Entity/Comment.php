<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\News", inversedBy="comments")
     */
    private $novelty;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNovelty(): ?News
    {
        return $this->novelty;
    }

    public function setNovelty(?News $novelty): self
    {
        $this->novelty = $novelty;

        return $this;
    }

    function getSeconds()
    {
        $currentDate = date('Y-m-d H:i:s');
        $currentDateStamp = strtotime($currentDate);
        $t = strtotime($this->getCreatedAt()->format('Y-m-d H:i:s'));
        $seconds = $currentDateStamp - $t;
        if ($seconds > 31556926) {
            return intval($seconds / 31556926) . " years ago";
        } elseif ($seconds > 2592000) {
            return intval($seconds / 2592000) . " months ago ";
        }elseif ($seconds > 604800) {
            return intval($seconds / 604800) . " weeks ago ";
        }elseif ($seconds > 86400) {
            return intval($seconds / 86400) . " days ago ";
        }elseif ($seconds > 3600) {
            return intval($seconds / 3600) . " ours ago ";
        }elseif ($seconds > 60) {
            return intval($seconds / 60) . " minutes ago ";
        }elseif ($seconds < 60) {
            return $seconds. " seconds ago ";
        }
    }
}
