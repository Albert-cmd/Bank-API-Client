<?php

namespace App\Repository;

use App\Entity\Compte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class CompteRepository
{
    //
    private $baseUrl;
    public function __construct()
    {
        $this->baseUrl = $_ENV['BASE_URL'];

    }


    function find ($id){

        $curl = curl_init();
        // configurem les opcions de la petició HTTP
        // completem la URL del web service per fer la petició GET dels clients
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->baseUrl . '/api/compteById/'.$id
        ));
        // executem la peticio cURL
        $respostaJ = curl_exec($curl);
        curl_close($curl);
        // la resposta ve en format Json, l’hem de convertir a format array de PHP
        $resposta = json_decode($respostaJ);

        $compte = new Compte();
        // cal afegir a la classe Client un metode setId
        $compte->setId($resposta->id);
        $compte->setCodi($resposta->codi);
        $compte->setSaldo($resposta->saldo);
        $compte->setClientId($resposta->client_id);


        return $compte;
    }

    function findAll(){

        $clientRepo = new ClientRepository();
        $curl = curl_init();
        // configurem les opcions de la petició HTTP
        // completem la URL del web service per fer la petició GET dels clients
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->baseUrl . '/api/comptes'
        ));
        // executem la peticio cURL
        $respostaJ = curl_exec($curl);
        curl_close($curl);
        // la resposta ve en format Json, l’hem de convertir a format array de PHP
        $resposta = json_decode($respostaJ);
        // construim un array amb objectes de la classe client
        $arrayComptes = array();
        foreach ($resposta as $row) {
            $compte = new Compte();
            // cal afegir a la classe Client un metode setId
            $compte->setId($row->id);
            $compte->setCodi($row->codi);
            $compte->setSaldo($row->saldo);
            $compte->setClientId($row->client_id);

            // asociamos cada cliente a cada cuenta.
            $clientTrobat =$clientRepo->find($compte->getClientId());
            $compte->setClient($clientTrobat);
            $arrayComptes[] = $compte;

        }
        return $arrayComptes;
    }

    function insert ($compte){

        $compte->setClient(null);

        $curl = curl_init();
        // configurem les opcions de la petició HTTP
        // completem la URL del web service per fer la petició GET dels clients
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->baseUrl. '/api/addCompte',
            CURLOPT_POSTFIELDS => http_build_query($compte)
        ));

        // executem la peticio cURL
        $respostaJ = curl_exec($curl);
        // la resposta ve en format Json, l’hem de convertir a format array de PHP
        $resposta = json_decode($respostaJ);

        $err = curl_error($curl);

        if (false === $respostaJ || !empty($err)) {

            $errno = curl_errno($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);
            $errorStr =  $errno.':'.$err.','.$info;
            return $errorStr;
        }

        curl_close($curl);

        $compte = new Compte();
        // cal afegir a la classe Client un metode setId

        $compte->setId($resposta->id);
        $compte->setCodi($resposta->codi);
        $compte->setSaldo($resposta->saldo);
        $compte->setClientId($resposta->client_id);

        return $compte;
    }
    function update($newCompte){

        $id =  $newCompte->getId();

        $curl = curl_init();
        // configurem les opcions de la petició HTTP
        // completem la URL del web service per fer la petició GET dels clients

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->baseUrl. '/api/compte/'.$id,
            CURLOPT_POSTFIELDS => http_build_query($newCompte)
        ));
        // executem la peticio cURL
        $respostaJ = curl_exec($curl);

        curl_close($curl);
        // la resposta ve en format Json, l’hem de convertir a format array de PHP
        $resposta = json_decode($respostaJ);

        $compte = new Compte();
        // cal afegir a la classe Client un metode setId
        $compte->setId($resposta->id);
        $compte->setCodi($resposta->codi);
        $compte->setSaldo($resposta->saldo);
        $compte->setClientId($resposta->client_id);

        return $compte;
    }

    function delete ($id){

        $curl = curl_init();
        // configurem les opcions de la petició HTTP
        // completem la URL del web service per fer la petició GET dels clients
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");


        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->baseUrl. '/api/compte/delete/'.$id
        ));

        // executem la peticio CURL
        $respostaJ = curl_exec($curl);

        echo $respostaJ;

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $resposta = json_decode($respostaJ);

        curl_close($curl);
        // la resposta ve en format Json, l’hem de convertir a format array de PHP


        $compte = new Compte();
        // cal afegir a la classe Client un metode setId
        $compte->setId($resposta->id);
        $compte->setCodi($resposta->codi);
        $compte->setSaldo($resposta->saldo);
        $compte->setClientId($resposta->client_id);

        return $compte;
    }

}
