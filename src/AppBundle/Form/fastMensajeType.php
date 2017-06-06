<?php

namespace AppBundle\Form;

use AppBundle\Entity\Mensaje;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class fastMensajeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder
            ->add('mensaje', TextareaType::class, [
                'label' => 'Mensaje',
                'required' => true,
                'attr' => [
                    'class' => 'form-mensaje',
                    'placeholder' => 'Escribe aquí tu mensaje'
                ]
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mensaje::class
        ]);
    }
}
