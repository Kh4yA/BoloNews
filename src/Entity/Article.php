<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $hat = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $auteur = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $creation_date_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $modification_date_at = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article', orphanRemoval:true)]
    private Collection $comments;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'articles')]
    private Collection $nb_like;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Category $category = null;

    #[ORM\Column]
    private ?bool $publish = null;

    /**
     * @var Collection<int, ArticleNumberOne>
     */
    #[ORM\OneToMany(targetEntity: ArticleNumberOne::class, mappedBy: 'article')]
    private Collection $articleNumberOnes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'idArticle')]
    private Collection $commentaire;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->nb_like = new ArrayCollection();
        $this->articleNumberOnes = new ArrayCollection();
        $this->commentaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHat(): ?string
    {
        return $this->hat;
    }

    public function setHat(string $hat): static
    {
        $this->hat = $hat;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getCreationDateAt(): ?\DateTimeImmutable
    {
        return $this->creation_date_at;
    }

    public function setCreationDateAt(?\DateTimeImmutable $creation_date_at): static
    {
        $this->creation_date_at = $creation_date_at;

        return $this;
    }

    public function getModificationDateAt(): ?\DateTimeImmutable
    {
        return $this->modification_date_at;
    }

    public function setModificationDateAt(?\DateTimeImmutable $modification_date_at): static
    {
        $this->modification_date_at = $modification_date_at;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getNbLike(): Collection
    {
        return $this->nb_like;
    }

    public function addNbLike(User $nbLike): static
    {
        if (!$this->nb_like->contains($nbLike)) {
            $this->nb_like->add($nbLike);
        }

        return $this;
    }

    public function removeNbLike(User $nbLike): static
    {
        $this->nb_like->removeElement($nbLike);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function isPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): static
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * @return Collection<int, ArticleNumberOne>
     */
    public function getArticleNumberOnes(): Collection
    {
        return $this->articleNumberOnes;
    }

    public function addArticleNumberOne(ArticleNumberOne $articleNumberOne): static
    {
        if (!$this->articleNumberOnes->contains($articleNumberOne)) {
            $this->articleNumberOnes->add($articleNumberOne);
            $articleNumberOne->setArticle($this);
        }

        return $this;
    }

    public function removeArticleNumberOne(ArticleNumberOne $articleNumberOne): static
    {
        if ($this->articleNumberOnes->removeElement($articleNumberOne)) {
            // set the owning side to null (unless already changed)
            if ($articleNumberOne->getArticle() === $this) {
                $articleNumberOne->setArticle(null);
            }
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Comment $commentaire): static
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setIdArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Comment $commentaire): static
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdArticle() === $this) {
                $commentaire->setIdArticle(null);
            }
        }

        return $this;
    }
}
