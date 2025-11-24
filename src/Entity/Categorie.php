<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/*
 * Représente une catégorie de formations.
 *
 * Gère le nom de la catégorie et son association ManyToMany
 * avec les entités Formation.
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    /*
     * Identifiant unique de la catégorie.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /*
     * Nom de la catégorie
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    /**
     * Formations appartenant à cette catégorie
     * 
     * @var Collection<int, Formation>
     */
    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'categories')]
    private Collection $formations;

    /*
     * Constructeur.
     * Initialise la collection des formations
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /*
     * Retourne l'identifiant unique.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /*
     * Retourne le nom de la catégorie.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /*
     * Définit le nom de la catégorie.
     *
     * @param string|null $name
     * @return static
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne les formations associées à cette catégorie.
     * 
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /*
     * Ajoute une formation à cette catégorie.
     *
     * @param Formation $formation
     * @return static
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->addCategory($this);
        }

        return $this;
    }

    /*
     * Supprime une formation de cette catégorie.
     *
     * @param Formation $formation
     * @return static
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeCategory($this);
        }

        return $this;
    }
}
