<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use Doctrine\ORM\Mapping as ORM;


class Compte
{
    public $client;
    public $id;
    public $codi;
    public $saldo;
    public $client_id;


    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id){
        $this->id = $id;
        return $this;
    }

    public function getCodi()
    {
        return $this->codi;
    }

    public function setCodi($codi)
    {
        $this->codi = $codi;

        return $this;
    }

    public function getSaldo()
    {
        return $this->saldo;
    }

    public function setSaldo(int $saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setClientId($client)
    {
        $this->client_id = $client;

        return $this;
    }

    public function getClient(){

         return $this->client;
    }

    public function setClient($client){
        $this->client=$client;
    }

    public function getClientNames(){

        return $this->client->getNomCognoms();

    }




}
