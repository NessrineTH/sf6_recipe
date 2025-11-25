<?php

namespace App\Controller\Frontend;

use App\Controller\CommonBaseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * BaseController controller.
 */
class BaseController extends CommonBaseController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly KernelInterface $kernel,
    ) {
        parent::__construct($em);
    }

    public function flashMessage(string $message, string $title = 'Information'): void
    {
        $this->addFlash('info', '<b>' . $title . ' :</b> ' . $message);
    }


    public function getRecipes()
    {
        $root = $this->kernel->getProjectDir();
        $recipes = json_decode(file_get_contents($root . '/recipes.json'), true);
        $recipes = $recipes['recettes'];

        return $recipes;
    }
}
