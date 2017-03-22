<?php

namespace AppBundle\Form;

use Doctrine\ORM\Query\Expr\Select;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AddViajeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('origen', TextType::class, array(
                'label' => 'Origen',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-origen form-control'
                )
            ))
            ->add('destino', TextType::class, array(
                'label' => 'Destino',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-destino form-control'
                )
            ))
            ->add('plazasLibres', NumberType::class, array(
                'label' => 'Plazas libres',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-plazas form-control'
                )
            ))
            ->add('diasRutina', TextType::class, array(
                'label' => 'Días',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-dias form-control'
                )
            ))
//            ->add('Lunes-Viernes', CheckboxType::class, array(
//                'required' => 'required',
//                'attr' => array(
//                    'class' => 'form-check form-control'
//                )
//            ))
            ->add('maximoAtras', CheckboxType::class, array(
                'label' => 'Máx. 2 pasajeros atrás',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-max'
                )
            ))
            ->add('flexiblididad', TextType::class, array(
                'label' => 'Flexibilidad',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-flexibilidad form-control'
                )
            ))
            ->add('Añadir', SubmitType::class, array(
                "attr" => array(
                    "class" => "form-submit btn btn-success"
                )
            ))
       ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Viaje'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_viaje';
    }


}
