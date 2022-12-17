<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, ChoiceField, DateTimeField, IdField, ImageField, IntegerField, NumberField, TextEditorField, TextField};

use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('seller', 'Vendeur')->autocomplete(),
            TextField::new('name', 'Nom du produit'),
            TextField::new('excerpt', 'Petite description'),
            TextEditorField::new('description')->setFormType(CKEditorType::class)->hideOnIndex(),
            IntegerField::new('quantity', 'Quantité'),
            NumberField::new('price', 'Prix'),
            ChoiceField::new('statut')->setChoices([
                "Indisponible" => 0,
                "Disponible" => 1,
            ]),
            AssociationField::new('category', 'Catégorie')->hideOnIndex(),
            AssociationField::new('brand', 'Marque')->autocomplete()->hideOnIndex(),
            IntegerField::new('sold', 'Promotions')->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
}
