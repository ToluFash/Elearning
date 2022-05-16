<?php

namespace App\Controller\Admin;

use App\Entity\CourseWeek;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CourseWeekCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseWeek::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield 'cardinality';
        yield AssociationField::new('course')
            ->setFormTypeOption('choice_label', 'title')
            ->setFormTypeOption('by_reference', 'id');
    }
}
