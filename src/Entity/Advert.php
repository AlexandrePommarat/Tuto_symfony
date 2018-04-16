<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="App\Repository\AdvertRepository")
 */
class Advert
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"remove"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Avis", mappedBy="advert")
     */
    private $avis;

    /**
     * on défini les méthodes pour ajouter et supprimer
     */
    public function addAvis(Avis $avis)
    {
        $this->avis[] = $avis;

        // On lie l'annonce à la candidature
        $avis->setAdvert($this);

        return $this;
    }

    public function removeAvis(Avis $avis)
    {
        $this->avis->removeElement($avis);

        // Et si notre relation était facultative (nullable=true, ce qui n'est pas notre cas ici attention) :
        // $application->setAdvert(null);
    }

    public function _construct()
    {
        $this->avis = new ArrayCollection();
        // ...
    }


    public function getAvis()
    {
        return $this->avis;
    }


    /**
     * @var Oeuvre
     * @ORM\ManyToOne(targetEntity="App\Entity\Oeuvre", inversedBy="advert")
     * @ORM\JoinColumn(nullable=true)
     */
    private $oeuvre;

    /**
     * @return mixed
     */
    public function getOeuvre()
    {
        return $this->oeuvre;
    }

    /**
     * @param mixed $oeuvre
     */
    public function setOeuvre($oeuvre): void
    {
        $this->oeuvre = $oeuvre;
    }



    /**
     * @return \Datetime
     */
    public function getDate(): \Datetime
    {
        return $this->date;
    }

    /**
     * @param \Datetime $date
     */
    public function setDate(\Datetime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    public function __construct()
    {
        $this->date       = new \Datetime();
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $dateajout;

    /**
     * @return \DateTime
     */
    public function getDateajout(): \DateTime
    {
        return $this->dateajout;
    }

    /**
     * @param \DateTime $dateajout
     */
    public function setDateajout(\DateTime $dateajout): void
    {
        $this->dateajout = $dateajout;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }


    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getId()
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

    /**
     * Set image
     *
     * @param \App\Entity\Image $image
     *
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;


    }
}
