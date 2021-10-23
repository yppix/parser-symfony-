<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url',TextType::class, array(
                'label' => 'Введите новостной URL',
                'required'=>true,
                'help' => 'Insert URL of website you want to parse.',

            ))
//            ->add('basic', TextType::class, ['label' => 'Введите родительский элемент',])
//            ->add('text', TextType::class, ['label' => 'Введите текстовый элемент',])
//            ->add('helper', TextType::class, ['label' => 'Введите вспомогательный элемент',])
//            ->add('img', TextType::class, ['label' => 'Введите элемент изображения',])
//            ->add('link', TextType::class, ['label' => 'Введите ссылочный элемент',])
            ->add('submit', SubmitType::class, ['label'=>'Получить данные'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
