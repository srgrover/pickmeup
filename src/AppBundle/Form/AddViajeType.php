<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

class AddViajeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('origen', TextType::class, [
                'label' => 'Origen',
                'required' => true,
                'attr' => [
                    'class' => 'form-origen text-success',
                    'placeholder' => 'ej. Paseo de las palmeras, Bailén',
                ]
            ])
            ->add('destino', TextType::class, [
                'label' => 'Destino',
                'required' => true,
                'attr' => [
                    'class' => 'form-destino text-danger',
                    'placeholder' => 'ej. Estadio Santiago Bernabéu',
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
                    'placeholder' => 'ej. 5'
                ]
            ])
            ->add('fechaSalida', null, [
                'label' => 'Fecha de salida',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'Fida',
                    'placeholder'=>'dd/mm/aaaa hh:mm'
                ],
                'format' => 'Y/M/d HH:mm',
            ])
//            ->add('horaSalidaIda', TimeType::class, [
//                'label' => 'Hora de salida',
//                'required' => true,
//                'widget' => 'single_text',
//                'html5' => false,
//                'attr' => [
//                    'class' => 'horaSalida',
//                    'placeholder'=>'hh:mm'
//                ],
//            ])
            ->add('fechaVuelta', null, [
                'label' => 'Fecha de vuelta',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'Fvuelta',
                    'placeholder'=>'dd/mm/aaaa hh:mm'
                ],
                'format' => 'Y/M/d HH:mm',

            ])
//            ->add('horaSalidaVuelta', TimeType::class, [
//                'label' => 'Hora de Vuelta',
//                'required' => true,
//                'html5' => false,
//                'widget' => 'single_text',
//                'attr' => [
//                    'class' => 'horaVuelta',
//                    'placeholder'=>'hh:mm'
//                ],
//            ])
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
                'required' => 'required',
                'attr' => [
                    'class' => 'form-desc form-control'
                ]
            ]);
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
