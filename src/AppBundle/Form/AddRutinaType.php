<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AddRutinaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('origen', TextType::class, [
                'label' => 'Origen',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-origen form-control'
                ]
            ])
            ->add('destino', TextType::class, [
                'label' => 'Destino',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-destino form-control'
                ]
            ])
            ->add('plazasLibres', NumberType::class, [
                'label' => 'Plazas libres',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-plazas form-control'
                ]
            ])
            ->add('precio', MoneyType::class, [
                'label' => 'Precio',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-precio form-control'
                ]
            ])
            ->add('diasRutina', TextType::class, [
                'label' => 'Días',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-dias form-control'
                ]
            ])
            ->add('horaSalidaIda', TimeType::class, [
                'label' => 'Hora de salida',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-hora-salida'
                ]
            ])
//            ->add('Lunes-Viernes', CheckboxType::class, array(
//                'required' => 'required',
//                'attr' => array(
//                    'class' => 'form-check form-control'
//                )
//            ))
            ->add('maximoAtras', CheckboxType::class, [
                'label' => 'Máx. 2 pasajeros atrás',
                'required' => false,
                'attr' => [
                    'class' => 'form-max'
                ]
            ])
            ->add('flexiblididad', ChoiceType::class, [
                'label' => 'Flexibilidad',
                'required' => 'required',
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
                    'class' => 'form-desc form-control'
                ]
            ])
            ->add('Añadir', SubmitType::class, [
                "attr" => [
                    "class" => "form-submit btn btn-success"
                ]
            ])
       ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Viaje'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_viaje';
    }


}
