<?php

namespace App\Form;

use App\Repository\CompteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Compte;

class TransferenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $compteRepo = new CompteRepository();
        $comptes = $compteRepo->findAll();

        $builder
            ->add('quantitat', IntegerType::class)
            ->add('compteOrigen',ChoiceType::class,[
                'choices'  => [
                    $comptes
                ],
                'choice_value' => 'id',
                'choice_label' => function(?Compte $compte) {
                    return $compte ? strtoupper($compte->getCodi()) : '';
                }
            ])
            ->add('compteDesti',ChoiceType::class,[
                'choices'  => [
                    $comptes
                ],
                'choice_value' => 'id',
                'choice_label' => function(?Compte $compte) {
                    return $compte ? strtoupper($compte->getCodi()) : '';
                }
            ])
            ->add('save', SubmitType::class, array('label' => 'Envia'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

}
