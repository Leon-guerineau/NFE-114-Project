<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comment')]
class Comment
{
    // Fields

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private ?string $content = null;

    #[ORM\Column(name:'created_at', type:'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(name:'updated_at', type:'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    // Relations

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    private ?Post $post = null;

    // Functions

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        $this->setUpdatedAt(new \DateTimeImmutable());

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }


    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
