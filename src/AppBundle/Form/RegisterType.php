<?php

namespace AppBundle\Form;

use AppBundle\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;

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
                'required' => true,
                'attr' => [
                    'class' => 'form-name form-control'
                ]
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos',
                'required' => true,
                'attr' => [
                    'class' => 'form-surname form-control'
                ]
            ])
            ->add('nick', TextType::class, [
                'label' => 'Nick',
                'required' => true,
                'attr' => [
                    'class' => 'form-nick form-control nick-input'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'required' => true,
                'attr' => [
                    'class' => 'form-email form-control'
                ]
            ])
//            ->add('password', PasswordType::class, [
//                'label' => 'Contraseña',
//                'required' => true,
//                'attr' => [
//                    'class' => 'form-password form-control'
//                ],
//                'constraints' => [
//                    new Length([
//                        'min' => 8
//                    ])
//                ]
//            ])
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'required' => true,
                'years' => range(1930,Date('Y')),
                'placeholder' => [
                    'day' => 'Día', 'month' => 'Mes', 'year' => 'Año'
                ],
                'attr' => [
                    'class' => 'form-date'
                ]
            ])
            ->add('ciudad', TextType::class, [
                'label' => 'Ciudad',
                'required' => true,
                'attr' => [
                    'class' => 'form-city form-control'
                ]
            ])
            ->add('provincia', TextType::class, [
                'label' => 'Provincia',
                'required' => true,
                'attr' => [
                    'class' => 'form-city form-control'
                ]
            ])
            ->add('pais', TextType::class, [
                'label' => 'País',
                'required' => true,
                'attr' => [
                    'class' => 'form-city form-control'
                ]
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'required' => true,
                'attr' => [
                    'class' => 'form-tlf form-control'
                ]
            ]);
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
