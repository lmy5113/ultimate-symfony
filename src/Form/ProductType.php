<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => 'Nom du produit',
            'attr' => ['placeholder' => 'Tapez le nom du produit'],
            'required' => false,
            // 'constraints' => new NotBlank(['message' => "Validation du formulaire : le nom du produit ne peut pas être vide"])
        ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr' => ['placeholder' => 'Tapez une description assez courte mais parlante']
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit',
                'attr' => ['placeholder' => 'Tapez le prix du produit en euro'],
                'divisor' => 100
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit',
                'attr' => ['placeholder' => 'Tapez une URL d\'image']
            ])->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return $category->getName();
                }
            ]);
        //transform euro <=> centime
        // $builder->get('price')->addModelTransformer(new CentimesTransformer());    

        //form lifecycle => pre_set_data post_set_data pre_submit_data post_submit_data    
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            // $form = $event->getForm();
            // /** @var Product */
            // $product = $event->getData();

        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
