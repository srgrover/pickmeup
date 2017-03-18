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
//use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserType extends AbstractType
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
                    'class' => 'form-nick form-control'
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Correo electrónico',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-email form-control'
                )
            ))
            ->add('descripcion', TextareaType::class, array(
                'label' => 'Biografia',
                'required' => false,
                'attr' => array(
                    'class' => 'form-bio form-control'
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
            ->add('imagenPerfil', FileType::class, array(
                'label' => 'Imagen de perfil',
                'required' => false,
                'data_class' => null,
                'attr' => array(
                    'class' => 'form-imagenPerfil form-control'
                )
            ))
            ->add('imagenFondo', FileType::class, array(
                'label' => 'Imagen de fondo',
                'required' => false,
                'data_class' => null,
                'attr' => array(
                    'class' => 'form-imgfondo form-control'
                )
            ))
            ->add('Guardar', SubmitType::class, array(
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
