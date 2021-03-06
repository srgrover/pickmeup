<?php

namespace AppBundle\Form;

use AppBundle\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
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
                    'class' => 'form-name'
                ]
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos',
                'required' => true,
                'attr' => [
                    'class' => 'form-surname'
                ]
            ])
            ->add('nick', TextType::class, [
                'label' => 'Nick',
                'required' => true,
                'attr' => [
                    'class' => 'nick-input',
                ],
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'required' => true,
                'attr' => [
                    'class' => 'form-email',
                ],
                'disabled' => true
            ])
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'required' => true,
                'years' => range(1930,Date('Y')-18),
                'placeholder' => [
                    'day' => 'Día', 'month' => 'Mes', 'year' => 'Año'
                ],
                'attr' => [
                    'class' => 'form-date'
                ]
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Biografia',
                'required' => false,
                'attr' => [
                    'class' => 'form-bio'
                ]
            ])
            ->add('ciudad', TextType::class, [
                'label' => 'Ciudad',
                'required' => true,
                'attr' => [
                    'class' => 'form-city'
                ]
            ])
            ->add('provincia', TextType::class, [
                'label' => 'Provincia',
                'required' => false,
                'attr' => [
                    'class' => 'form-provincia'
                ]
            ])
            ->add('pais', TextType::class, [
                'label' => 'País',
                'required' => false,
                'attr' => [
                    'class' => 'form-pais'
                ]
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'required' => true,
                'attr' => [
                    'class' => 'form-tlf'
                ]
            ])
            ->add('imagenPerfil', FileType::class, [
                'label' => 'Imagen de perfil',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'form-imagenPerfil'
                ]
            ])
            ->add('imagenFondo', FileType::class, [
                'label' => 'Imagen de fondo',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'form-imgfondo'
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
