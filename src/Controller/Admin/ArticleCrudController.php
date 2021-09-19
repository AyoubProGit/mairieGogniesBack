<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->addFormTheme('@VichUploader/Form/fields.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
               TextField::new('title'),
            TextareaField::new('content')->setLabel('Contenu')->setFormType(CKEditorType::class),
            BooleanField::new('is_online')->setPermission('ROLE_ADMIN'),
            TextareaField::new('imageFile')->setLabel('Image')->setFormType(VichImageType::class),
            AssociationField::new('author')->setPermission('ROLE_ADMIN'),
            AssociationField::new('tag'),


        ];
    }

}
