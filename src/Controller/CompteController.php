<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use FPDF;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Compte;
use App\Form\CompteType;
use App\Form\TransferenciaType;

class CompteController extends AbstractController
{

    /**
     * @Route("/compte/list", name="compte_list")
     */
    public function list()
    {
        $repoComptes = new CompteRepository();
        $comptes = $repoComptes->findAll();

        return $this->render('compte/list.html.twig', ['comptes' => $comptes]);
    }

    /**
     * @Route("/compte/new", name="compte_new")
     */
    public function new(Request $request)
    {
        $compteRepo = new CompteRepository();
        $compte = new Compte();

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe CompteType
        $form = $this->createForm(CompteType::class, $compte, array('submit'=>'Crear Compte'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte compte
            //$compte = $form->getData();
            $client = $form['client']->getData();
            $compte->setClientId($client->getId());
            $compte->setSaldo($form['saldo']->getData());
            $compte->setCodi($form['codi']->getData());



            $compteRepo->insert($compte);

            $this->addFlash(
                'notice',
                'Nou compte '.$compte->getCodi().' creat!'
            );
            return $this->redirectToRoute('compte_list');
        }

        return $this->render('compte/compte.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou Compte',
        ));
    }

    /**
     * @Route("/compte/edit/{id<\d+>}", name="compte_edit")
     */
    public function edit($id, Request $request)
    {
        $repoComptes = new CompteRepository();
        $repoClients = new ClientRepository();

        $arrClients = $repoClients->findAll();
        $compte =  $repoComptes->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe CompteType
        $form = $this->createForm(CompteType::class, $compte, array('submit'=>'Desar'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $compteRepo = new CompteRepository();

            // recollim els camps del formulari en l'objecte compte
           // $compte = $form->getData();

            $client = $form['client']->getData();
            $compte->setClientId($client->getId());
            $compte->setSaldo($form['saldo']->getData());
            $compte->setCodi($form['codi']->getData());

            $compteRepo->update($compte);



            $this->addFlash(
                'notice',
                'Compte '.$compte->getCodi().' desat!'
            );

            return $this->redirectToRoute('compte_list');
        }

        return $this->render('compte/compte.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar compte',
        ));
    }

    /**
     * @Route("/compte/delete/{id}", name="compte_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $repoComptes = new CompteRepository();

        $compte = $repoComptes->delete($id);

        $this->addFlash(
            'notice',
            'Compte '.$compte->getCodi().' eliminat!'
        );

        return $this->redirectToRoute('compte_list');
    }

    /**
     * @Route("/compte/transferencia", name="compte_transferencia")
     */
    public function transferencia(Request $request)
    {
        $repoComptes = new CompteRepository();


        $form = $this->createForm(TransferenciaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



          // recollim les ids dels comptes origen i destinacio
          $idOrigen = $form->get('compteOrigen')->getData();
          $idOrigen = $idOrigen->getId();

          $idDestinacio = $form->get('compteDesti')->getData();
          $idDestinacio = $idDestinacio->getId();

          // recollim la quantitat a transferir
          $quantitat = $form->get('quantitat')->getData();

          // cerquem els objectes Compte en la BDD
          $compteOrigen = $repoComptes->find($idOrigen);
          $compteDestinacio = $repoComptes->find($idDestinacio);


          /* si els comptes origen i destinacio son el mateix, recarreguem el
            formulari amb un missatge de validacio */

          if ($compteOrigen == $compteDestinacio) {

            $this->addFlash(
                'notice',
                'Els comptes origen i destinació han de ser diferents'
            );

            return $this->redirectToRoute('compte_transferencia');
          }

          // modifiquem el saldo dels comptes
          // un saldo s'incrementa en $quantitat, l'altre es disminueux

          $saldoOrigen = $compteOrigen->getSaldo();
          $saldoDestinacio = $compteDestinacio->getSaldo();

          $saldoOrigen -= $quantitat;
          $saldoDestinacio += $quantitat;

          $compteOrigen->setSaldo($saldoOrigen);
          $compteDestinacio->setSaldo($saldoDestinacio);


            // hacemos update de los dos objetos.

            $repoComptes->update($compteOrigen);
            $repoComptes->update($compteDestinacio);


          $this->addFlash(
              'notice',
              'Transferència realitzada: '.$quantitat.' € des del compte '.$compteOrigen->getCodi().
                ' fins al compte '.$compteDestinacio->getCodi()
          );

          return $this->redirectToRoute('compte_transferencia');
        }

        return $this->render('compte/transferencia.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Transferència bancària',
        ));
    }


    /**
     * @Route("/report_comptes", name="report_comptes")
     */
    public function report_comptes()
    {
       // require('fpdf.php');

        $repoComptes = new CompteRepository();
        $comptes = $repoComptes->findAll();

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);

       // $pdf->LoadData();
        $header = array('Num.', 'Codi', 'Saldo', 'Client');

        // Header
        foreach ($header as $elem ) {
            $pdf->Cell(45,7,$elem,1);

        }
        $pdf->Ln();
        // Data
        foreach($comptes as $row)
        {
            $pdf->Cell(45,6,$row->getId(),1);
            $pdf->Cell(45,6,$row->getCodi(),1);
            $pdf->Cell(45,6,$row->getSaldo(),1);
            $pdf->Cell(45,6,$row->getClientNames(),1);
            $pdf->Ln();
        }

        $pdf->Output('','report_comptes.pdf');

        $response = new Response(
            'Content',
            Response::HTTP_OK,
            array('content-type' => 'application/pdf')
        );
        return $response;
    }
    /**
     * @Route("/report_clients", name="report_clients")
     */
    public function report_clients()
    {
        // require('fpdf.php');

        $repoClients = new ClientRepository();
        $clients = $repoClients->findAll();

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);

        // $pdf->LoadData();
        $header = array('DNI', 'Nom', 'Cognoms', 'Data Naixement');

        // Header
        foreach ($header as $elem ) {
            $pdf->Cell(45,7,$elem,1);

        }
        $pdf->Ln();
        // Data
        foreach($clients as $row)
        {
            $pdf->Cell(45,6,$row->getDni(),1);
            $pdf->Cell(45,6,$row->getNom(),1);
            $pdf->Cell(45,6,$row->getCognoms(),1);
            $pdf->Cell(45,6,$row->getDataN(),1);
            $pdf->Ln();
        }

        $pdf->Output('','report_comptes.pdf');

        $response = new Response(
            'Content',
            Response::HTTP_OK,
            array('content-type' => 'application/pdf')
        );
        return $response;
    }

    // Simple table
    function BasicTable($header, $data)
    {

    }




}
