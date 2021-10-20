<?php

namespace App\Controller\Admin;

use App\Entity\Trip;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SortieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trip::class;
    }


}
