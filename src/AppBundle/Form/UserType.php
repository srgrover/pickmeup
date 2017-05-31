<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
//use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
                'required' => 'required',
                'attr' => [
                    'class' => 'form-name'
                ]
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-surname'
                ]
            ])
            ->add('nick', TextType::class, [
                'label' => 'Nick',
                'required' => true,
                'attr' => [
                    'class' => 'form-nick disabled',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-email',
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
            'data_class' => 'AppBundle\Entity\Usuario'
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
