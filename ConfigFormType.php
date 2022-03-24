<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigFormType extends \Symfony\Component\Form\AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, ['attr' => ['class' => 'form-control']])
            ->add('token', TextType::class, ['attr' => ['class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'integration' => null,
            ]
        );
    }
}
