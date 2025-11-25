<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\AdminGroupe;
use App\Entity\AdminUtilisateur;
use App\Repository\AdminGroupeRepository;
use App\Repository\AdminUtilisateurRepository;
use App\Service\DataGrid\DataGridParam;
use App\Service\ParamService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * CommonBaseController controller.
 */
class CommonBaseController extends AbstractController
{
    private EntityManagerInterface $em;
    private ParamService $paramManager;


    public function __construct(EntityManagerInterface $em, ParamService $paramManager)
    {
        $this->em = $em;
        $this->paramManager = $paramManager;
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

    protected function getParamManager(): ParamService
    {
        return $this->paramManager;
    }
}
