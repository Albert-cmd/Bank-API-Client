<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class Client
{

    public $id;
    public $dni;
    public $nom;
    public $cognoms;
    public $dataN;
    public function getNomCognoms()
    {
        return $this->nom . ' ' . $this->cognoms;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDni()
    {
        return $this->dni;
    }

    public function setDni(string $dni)
    {
        $this->dni = $dni;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCognoms()
    {
        return $this->cognoms;
    }

    public function setCognoms(string $cognoms)
    {
        $this->cognoms = $cognoms;

        return $this;
    }

    public function getDataN()
    {
        return $this->dataN;
    }

    public function setDataN($dataN)
    {
        $this->dataN = $dataN;

        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


}
