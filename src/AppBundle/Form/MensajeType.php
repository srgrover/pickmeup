<?php

namespace AppBundle\Form;

use AppBundle\Entity\Mensaje;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MensajeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $usuario = $options['empty_data'];
        $builder
            ->add('receptor', EntityType::class, [
                'class' => 'AppBundle\Entity\Usuario',
                'query_builder' => function($er) use($usuario){
                    if ($usuario->isAdmin()){
                        return $er->getAllUsuarios();
                    }else{
                        return $er->getUsuariosSiguiendo($usuario);
                    }
                },
                'choice_label' => function($usuario){
                    return $usuario->getNombre()." ".$usuario->getApellidos();
                },
                'label' => 'Para: ',
                'required' => true,
                'attr' => [
                    'class' => 'form-receptor'
                ]
            ])
            ->add('mensaje', TextareaType::class, [
                'label' => 'Mensaje',
                'required' => 'required',
                'attr' => [
                    'class' => 'form-mensaje'
                ]
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mensaje::class
        ]);
    }
}
