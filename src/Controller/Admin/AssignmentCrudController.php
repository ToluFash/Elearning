<?php

namespace App\Controller\Admin;

use App\Entity\Assignment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class AssignmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Assignment::class;
    }

    public function configureFields(string $pageName): iterable
    {

        yield 'title';
        yield AssociationField::new('Course')
            ->setFormTypeOption('choice_label', 'title')
            ->setFormTypeOption('by_reference', 'id');
        yield AssociationField::new('Instructor')
            ->setFormTypeOption('choice_label', 'user.username');
        yield AssociationField::new('courseWeek')
            ->setFormTypeOption('choice_label', 'getChoiceName');


    }
}
