<?php

namespace App\Form;

use App\Repository\ClientRepository;
use SebastianBergmann\CodeCoverage\TestFixture\C;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Client;

class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $clientRepo = new ClientRepository();
        $clients = $clientRepo->findAll();


        $builder
            ->add('codi', TextType::class)
            ->add('saldo', IntegerType::class)
            ->add('client',ChoiceType::class,[
                'choices'  => [
                    $clients
                ],
                'choice_value' => 'id',
                'choice_label' => function(?Client $client) {
                    return $client ? strtoupper($client->getNomCognoms()) : '';
                }
            ])
           /* ->add('client', EntityType::class, array('class' => Client::class,
            //'choice_label' => 'nom'))
            'choice_label' => function ($client) {
                                return $client->getNom() . ' ' . $client->getCognoms(); }
            ))*/
            ->add('save', SubmitType::class, array('label' => $options['submit']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'submit' => 'Enviar',
        ]);
    }

}
