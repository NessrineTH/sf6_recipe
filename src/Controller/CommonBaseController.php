<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * CommonBaseController controller.
 */
class CommonBaseController extends AbstractController
{
    private EntityManagerInterface $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addFlashNoticeSaved(string $message = null): void
    {
        if (null === $message) {
            $message = 'Les informations ont été enregistrées';
        }
        $this->addFlash('warning', $message);
    }

    protected function getEm(): EntityManagerInterface
    {
        return $this->em;
    }
}
