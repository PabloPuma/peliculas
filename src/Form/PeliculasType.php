<?php

namespace App\Form;

use App\Entity\Peliculas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeliculasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
//            ->add('likes')
            ->add('foto',FileType::class,['label' => 'Seleccione una imágen','mapped' => false,'required' => false])
//            ->add('fecha_publicacion')
            ->add('resumen', TextareaType::class)
//            ->add('user')
            ->add('Guardar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Peliculas::class,
        ]);
    }
}
