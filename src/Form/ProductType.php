<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom Du Produit', 'attr' => ['placeholder' => 'Tapez le nom du produit']])
            ->add('shortDescription', TextareaType::class, ['label' => 'Description Du Produit', 'attr' => ['placeholder' => 'Donner une courte description mais parlante pour le visiteur']])
            ->add('price', MoneyType::class, ['label' => 'Prix Du Produit', 'attr' => ['placeholder' => 'Le prix du produit en €']])
            ->add('mainPicture', UrlType::class, [
                'label' => "Image du produit",
                'attr' => ['placeholder' => "Taper une url d'image "]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categorie',
                'placeholder' => '--Choisir une categorie--',
                'class' => Category::class,
                'choice_label' => 'name'
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
