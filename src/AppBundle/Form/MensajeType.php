<?php

namespace AppBundle\Form;

use AppBundle\Entity\Mensaje;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MensajeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('receptor', TextType::class, array(
//                'label' => 'Nombre',
//                'required' => 'required',
//                'attr' => array(
//                    'class' => 'form-receptor'
//                )
//            ))
            ->add('mensaje', TextareaType::class, array(
                'label' => 'Mensaje',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-mensaje'
                )
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Mensaje::class
        ));
    }
}
