<?php

namespace App\Controller\Admin;

use App\Entity\Profiles;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProfilesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Profiles::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            IdField::new('user_id'),
            TextField::new('picture'),
            TextEditorField::new('description'),
            DateTimeField::new('birthday'),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt'),
        ];
    }
}
