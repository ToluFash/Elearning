<?php

namespace App\Controller\Admin;

use App\Entity\Instructor;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class InstructorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Instructor::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('User')
            ->setFormTypeOption('choice_label', 'username')
            ->setFormTypeOption('by_reference', 'id');
        yield AssociationField::new('department')
            ->setFormTypeOption('choice_label', 'name')
            ->setFormTypeOption('by_reference', 'id');
    }
}
