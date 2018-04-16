<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="oeuvre")
 * @ORM\Entity(repositoryClass="App\Repository\OeuvreRepository")
 */
class Oeuvre
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advert", mappedBy="oeuvre")
     */
    private $advert;

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    // Notez le singulier, on ajoute une seule catégorie à la fois
    public function addCategory(Category $category)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->categories[] = $category;
    }

    public function removeCategory(Category $category)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la catégorie en argument
        $this->categories->removeElement($category);
    }

    public function __construct()
    {
        $this->date         = new \Datetime();
        $this->categories = new ArrayCollection();
    }


    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", cascade={"persist"})
     */
    private $categories;

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

}
