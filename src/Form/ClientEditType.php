<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $avui = new \DateTime();
        $builder
            ->add('dni', TextType::class)
            ->add('nom', TextType::class)
            ->add('cognoms', TextType::class)
            //->add('dataN', DateType::class, array('widget' => 'choice'))
            ->add('dataN', DateType::class, array(
              'widget' => 'single_text',
              'label' => 'Data de naixement'
            ))
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
