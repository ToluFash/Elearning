<?php

namespace App\Controller\Admin;

use App\Entity\Instructor;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InstructorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Instructor::class;
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
