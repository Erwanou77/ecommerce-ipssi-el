<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "",
                "attr" => [
                    "class" => "input",
                ]
            ])
            ->add('excerpt', TextType::class, [
                "label" => "descriptif",
                "attr" => [
                    "class" => "input",
                ],
                "required" => false,
            ])
            ->add('description', CKEditorType::class, [
                "label" => "",
                "required" => false,
            ])
            ->add('image', TextType::class, [
                "label" => "",
                "attr" => [
                    "class" => "input",
                ],
                "required" => false,
            ])
            ->add('quantity', IntegerType::class, [
                "label" => "Quantité",
                "attr" => [
                    "class" => "input",
                ],
                "required" => false,
            ])
            ->add('price', NumberType::class, [
                "label" => "Prix",
                "attr" => [
                    "class" => "input",
                ],
                "required" => false,
            ])
            ->add('statut', ChoiceType::class, [
                "label" => "Status",
                "choices" => [
                    "Indisponible" => 0,
                    "Disponible" => 1,
                ],
                "attr" => [
                    "class" => "input",
                ],
                "required" => false,
            ])
            ->add('category', EntityType::class, [
                "class" => Category::class,
                "label" => "Catégories",
                "choice_label" => "name",
                "attr" => [
                    "class" => "input",
                ],
                "required" => false,
            ])
            ->add('sold', IntegerType::class, [
                "label" => "Promotions",
                "attr" => [
                    "class" => "input"
                ],
                "required" => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
