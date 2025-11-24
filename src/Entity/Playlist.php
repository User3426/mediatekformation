<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/*
 * Représente une playlist de formations.
 *
 * Contient un nom, une description, et une liste de formations
 * associées à cette playlist. Fournit également des méthodes utilitaires
 * pour récupérer les catégories présentes dans la playlist et le nombre total de formations.
 */
#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    /*
     * Identifiant unique de la playlist.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /*
     * Nom de la playlist.
     */
    #[ORM\Column(length: 100, nullable: false)]
    private ?string $name = null;

    /*
     * Description textuelle de la playlist.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Formations rattachées à cette playlist
     * 
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'playlist')]
    private Collection $formations;

    /*
     * Constructeur.
     * Initialise la collection des formations.
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
     * Retourne le nom de la playlist.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /*
     * Définit le nom de la playlist.
     *
     * @param string|null $name
     * @return static
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /*
     * Retourne la description de la playlist.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /*
     * Définit la description de la playlist.
     *
     * @param string|null $description
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne les formations de la playlist.
     * 
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /*
     * Ajoute une formation à la playlist.
     *
     * @param Formation $formation
     * @return static
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setPlaylist($this);
        }

        return $this;
    }

    /*
     * Supprime une formation de la playlist.
     *
     * @param Formation $formation
     * @return static
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation) && $formation->getPlaylist() === $this) {
            $formation->setPlaylist(null);
        }

        return $this;
    }
    
    /**
     * Retourne la liste des catégories présentes dans les formations de la playlist.
     * 
     * @return Collection<int, string>
     */
    public function getCategoriesPlaylist() : Collection
    {
        $categories = new ArrayCollection();
        foreach($this->formations as $formation){
            $categoriesFormation = $formation->getCategories();
            foreach($categoriesFormation as $categorieFormation){
                if(!$categories->contains($categorieFormation->getName())){
                    $categories[] = $categorieFormation->getName();
                }
            }
        }
        return $categories;
    }
    
    /*
     * Retourne le nombre de formations présentes dans la playlist.
     *
     * @return int
     */
    public function getNbFormations(): int
    {
        return $this->formations->count();
    }
        
}
