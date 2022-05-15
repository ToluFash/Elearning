<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use App\Entity\Faculty;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class FacultyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Faculty::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield 'name';
    }
}
