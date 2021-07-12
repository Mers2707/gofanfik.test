<?php

namespace App\Entity;

use App\Entity\Fanfik;
use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=Fanfik::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $fanfik;

    /**
     * @ORM\OneToMany(targetEntity=ArticleSection::class, mappedBy="article", cascade={"persist"})
     * @ORM\OrderBy({"number" = "ASC"})
     */
    private $section_id;

    public function __construct()
    {
        $this->section_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getFanfik(): ?Fanfik
    {
        return $this->fanfik;
    }

    public function setFanfik(Fanfik $fanfik): self
    {
        $this->fanfik = $fanfik;

        return $this;
    }

    /**
     * @return Collection|ArticleSection[]
     */
    public function getSectionId(): Collection
    {
        return $this->section_id;
    }

    public function addSectionId(ArticleSection $sectionId): self
    {
        if (!$this->section_id->contains($sectionId)) {
            $this->section_id[] = $sectionId;
            $sectionId->setArticle($this);
        }

        return $this;
    }

    public function removeSectionId(ArticleSection $sectionId): self
    {
        if ($this->section_id->removeElement($sectionId)) {
            // set the owning side to null (unless already changed)
            if ($sectionId->getArticle() === $this) {
                $sectionId->setArticle(null);
            }
        }

        return $this;
    }

}
