<?php

namespace AppBundle\Form;

use AppBundle\Entity\Vehiculo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CocheType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marca', TextType::class, [
                'label' => 'Marca',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ej. Ford'
                ]
            ])
            ->add('modelo', TextType::class, [
                'label' => 'Modelo',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ej. Focus'
                ]
            ])
            ->add('color', TextType::class, [
                'label' => 'Color',
                'required' => true,
                'attr' => [
                    'placeholder' => 'ej. Azul'
                ]
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehiculo::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_vehiculo';
    }


}
