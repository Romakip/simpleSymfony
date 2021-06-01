<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="category")
     */
    private $acrticles;

    public function __construct()
    {
        $this->acrticles = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getAcrticles(): Collection
    {
        return $this->acrticles;
    }

    public function addAcrticle(Article $acrticle): self
    {
        if (!$this->acrticles->contains($acrticle)) {
            $this->acrticles[] = $acrticle;
            $acrticle->setCategory($this);
        }

        return $this;
    }

    public function removeAcrticle(Article $acrticle): self
    {
        if ($this->acrticles->removeElement($acrticle)) {
            // set the owning side to null (unless already changed)
            if ($acrticle->getCategory() === $this) {
                $acrticle->setCategory(null);
            }
        }

        return $this;
    }
}
