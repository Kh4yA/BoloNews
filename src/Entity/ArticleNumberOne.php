<?php

namespace App\Entity;

use App\Repository\ArticleNumberOneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleNumberOneRepository::class)]
class ArticleNumberOne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'articleNumberOnes')]
    private ?Article $article = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getDateAt(): ?\DateTimeImmutable
    {
        return $this->date_at;
    }

    public function setDateAt(?\DateTimeImmutable $date_at): static
    {
        $this->date_at = $date_at;

        return $this;
    }
}
