<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom Du Produit', 'attr' => ['placeholder' => 'Tapez le nom du produit'], 'required' => false])
            ->add('shortDescription', TextareaType::class, ['label' => 'Description Du Produit', 'attr' => ['placeholder' => 'Donner une courte description mais parlante pour le visiteur']])
            ->add('price', MoneyType::class, ['label' => 'Prix Du Produit', 'attr' => ['placeholder' => 'Le prix du produit en €'], 'divisor' => 100])
            ->add('mainPicture', UrlType::class, [
                'label' => "Image du produit",
                'attr' => ['placeholder' => "Taper une url d'image "]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Categorie',
                'placeholder' => '--Choisir une categorie--',
                'class' => Category::class,
                'choice_label' => 'name'
            ]);

        // $builder->get('price')->addModelTransformer(new CentimesTransformer);


        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     $product = $event->getData();
        //     if ($product->getPrice() != null) {
        //         $product->setPrice($product->getPrice() * 100);
        //     }
        // });

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();
        //     /** @var Product */
        //     $product = $event->getData();
        //     if ($product->getPrice() != null) {
        //         $product->setPrice($product->getPrice() / 100);
        //     }
        //     // if ($product->getId() === null) {
        //     //     $form->add('category', EntityType::class, [
        //     //         'label' => 'Categorie',
        //     //         'placeholder' => '--Choisir une categorie--',
        //     //         'class' => Category::class,
        //     //         'choice_label' => 'name'
        //     //     ]);
        //     // }
        // });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
