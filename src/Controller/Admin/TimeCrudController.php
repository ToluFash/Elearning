<?php

namespace App\Controller\Admin;

use App\Entity\Time;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Time::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
