<?php
namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ClientRepository
{

    private $baseUrl;
    public function __construct()
    {
       $this->baseUrl = $_ENV['BASE_URL'];

    }

    //➔ En el repositori del client, només necessitem els mètodes findAll, find i insert.

    public function findAll() {
        $curl = curl_init();
        // configurem les opcions de la petició HTTP
        // completem la URL del web service per fer la petició GET dels clients
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->baseUrl . '/api/clients'
        ));
        // executem la peticio cURL
        $respostaJ = curl_exec($curl);
        curl_close($curl);
        // la resposta ve en format Json, l’hem de convertir a format array de PHP
        $resposta = json_decode($respostaJ);
        // construim un array amb objectes de la classe client
        $arrayClients = array();
        foreach ($resposta as $row) {
            $client = new Client();
            // cal afegir a la classe Client un metode setId
            $client->setId($row->id);
            $client->setDni($row->dni);
            $client->setNom($row->nom);
            $client->setCognoms($row->cognoms);
            $client->setDataN($row->dataN);
            $arrayClients[] = $client;
        }
        return $arrayClients;
    }


    function find ($id){

        $curl = curl_init();
        // configurem les opcions de la petició HTTP
        // completem la URL del web service per fer la petició GET dels clients
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->baseUrl . '/api/clientById/'.$id
        ));
        // executem la peticio cURL
        $respostaJ = curl_exec($curl);
        curl_close($curl);
        // la resposta ve en format Json, l’hem de convertir a format array de PHP
        $resposta = json_decode($respostaJ);

            $client = new Client();
            // cal afegir a la classe Client un metode setId
            $client->setId($resposta->id);
            $client->setDni($resposta->dni);
            $client->setNom($resposta->nom);
            $client->setCognoms($resposta->cognoms);
            $client->setDataN($resposta->dataN);

        return $client;
    }

    function insert ($client){


       // var_dump($client);
       // exit();

        $curl = curl_init();
        // configurem les opcions de la petició HTTP
        // completem la URL del web service per fer la petició GET dels clients
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->baseUrl. '/api/addClient',
            CURLOPT_POSTFIELDS => http_build_query($client)
        ));
        // executem la peticio cURL
        $respostaJ = curl_exec($curl);

        curl_close($curl);
        // la resposta ve en format Json, l’hem de convertir a format array de PHP
        $resposta = json_decode($respostaJ);


        $client = new Client();
        // cal afegir a la classe Client un metode setId
        $client->setId($resposta->id);
        $client->setDni($resposta->dni);
        $client->setNom($resposta->nom);
        $client->setCognoms($resposta->cognoms);
        $client->setDataN($resposta->dataN);
        return $client;

    }


}
