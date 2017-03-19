<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'label' => 'Nombre',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-name form-control'
                )
            ))
            ->add('apellidos', TextType::class, array(
                'label' => 'Apellidos',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-surname form-control'
                )
            ))
            ->add('nick', TextType::class, array(
                'label' => 'Nick',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-nick form-control nick-input'
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Correo electrónico',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-email form-control email-input'
                )
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Contraseña',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-password form-control'
                )
            ))
            ->add('fechaNacimiento', DateType::class, array(
                'label' => 'Fecha de nacimiento',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-date form-control'
                )
            ))
            ->add('ciudad', TextType::class, array(
                'label' => 'Ciudad',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-city form-control'
                )
            ))
            ->add('telefono', TextType::class, array(
                'label' => 'Teléfono',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-tlf form-control'
                )
            ))
            ->add('Registrarse', SubmitType::class, array(
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
            'data_class' => 'AppBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_usuario';
    }


}
