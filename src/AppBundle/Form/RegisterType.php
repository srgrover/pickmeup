<?php

namespace AppBundle\Form;

use AppBundle\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-name form-control'
                ]
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-surname form-control'
                ]
            ])
            ->add('nick', TextType::class, [
                'label' => 'Nick',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-nick form-control nick-input'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-email form-control'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-password form-control'
                ],
                'constraints' => [
                    new Length([
                        'min' => 8
                    ])
                ]
            ])
            ->add('fechaNacimiento', BirthdayType::class, [
                'label' => 'Fecha de nacimiento',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-date'
                ]
            ])
            ->add('ciudad', TextType::class, [
                'label' => 'Ciudad',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-city form-control'
                ]
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-tlf form-control'
                ]
            ])
            ->add('Registrarse', SubmitType::class, [
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
            'data_class' => Usuario::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_usuario';
    }

}
