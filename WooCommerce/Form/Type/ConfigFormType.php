<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\WooCommerce\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, ['attr' => ['class' => 'form-control']])
            ->add('consumerKey', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('consumerSecret', TextType::class, ['attr' => ['class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'integration' => null,
            ]
        );
    }
}
