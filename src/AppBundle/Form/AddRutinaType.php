<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AddRutinaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $dias = $options['empty_data'];
        $builder
            ->add('origen', TextType::class, [
                'label' => 'Origen',
                'required' => true,
                'attr' => [
                    'class' => 'form-origen form-control',
                    'placeholder' => 'ej. Bailén'
                ]
            ])
            ->add('destino', TextType::class, [
                'label' => 'Destino',
                'required' => true,
                'attr' => [
                    'class' => 'form-destino form-control',
                    'placeholder' => 'ej. Linares'
                ]
            ])
            ->add('plazasLibres', ChoiceType::class, [
                'label' => 'Plazas libres',
                'required' => true,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4
                ],
                'attr' => [
                    'class' => 'form-plazas form-control'
                ],
                'placeholder' => 'Seleccione el número de plazas (máximo 4)'
            ])
            ->add('precio', MoneyType::class, [
                'label' => 'Precio',
                'required' => true,
                'attr' => [
                    'class' => 'form-precio form-control',
                    'placeholder' => 'ej. 1 (Por viaje)'
                ]
            ])
            ->add('dias', EntityType::class, [
                'class' => 'AppBundle\Entity\Usuario',
                'query_builder' => function($er) use($dias){
                    return $er->findAll();
                },
                'choice_label' => function($dias){
                    return $dias->getDia();
                },
                'label' => 'Para: ',
                'required' => true,
                'attr' => [
                    'class' => 'form-receptor'
                ]
            ])
            ->add('horaSalida', TimeType::class, [
                'label' => 'Hora de salida',
                'required' => true,
                'attr' => [
                    'class' => 'form-hora-salida'
                ],
                'placeholder' => [
                    'hour' => "Hora",
                    'minute' => 'Minuto'
                ]
            ])
            ->add('maximoAtras', CheckboxType::class, [
                'label' => 'Máx. 2 pasajeros atrás',
                'required' => false,
                'attr' => [
                    'class' => 'form-max'
                ]
            ])
            ->add('flexiblididad', ChoiceType::class, [
                'label' => 'Flexibilidad',
                'required' => true,
                'choices'  => [
                    'Justo a tiempo' => 'Justo a tiempo',
                    'En +/- 15 minutos' => 'En +/- 15 minutos',
                    'En +/- 30 minutos' => 'En +/- 30 minutos',
                    'En +/- 1 hora' => 'En +/- 1 hora',
                    'En + de 1 hora' => 'En + de 1 hora',
                ],
                'attr' => [
                    'class' => 'form-flexibilidad'
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Anotaciones del viaje',
                'required' => false,
                'attr' => [
                    'class' => 'form-desc'
                ]
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Rutina'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_rutina';
    }
}
