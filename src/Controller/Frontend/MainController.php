<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Service\ThemeAppstackMenuService;
use App\Tools\CoreAdminzDroits;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;


class MainController extends BaseController
{
    public function topbar(RequestStack $request, ThemeAppstackMenuService $menuService, Security $security, $section = null): Response
    {
        $masterRequest = $request->getMainRequest();
        $currentRoute = $masterRequest->getRequestUri();
        $menuItems = [];



        return $this->render('frontend/main/topbar.html.twig', [
            'menu' => $menuService->generer($menuItems, $currentRoute),
            'menuSupplementaire' => [],
            'section' => $section,
        ]);
    }

    public function sidebar(RequestStack $request, ThemeAppstackMenuService $menuService, Security $security, $section = null): Response
    {
        $masterRequest = $request->getMainRequest();
        $currentRoute = $masterRequest->getRequestUri();
        $menuSubItems = [];
        $i = 0;

        $menuSubItems[] = $menuService->menuItemFromRoute($currentRoute, 'Accueil', 'frontend_index', [], ['iclass' => 'key']);
        $menuSubItems[] = $menuService->menuItemFromRoute($currentRoute, 'Produits', 'produit_index', [], ['iclass' => 'key']);

        /* 
        // exemple menu avec sous-menu
        $mAdd = $menuService->menuItemParent('Template', 'box');
        $mAdd = $menuService->menuSubItemFromRoute($mAdd, ++$i, $currentRoute, 'Fiche', 'coreadminz_template_fiche');
        $mAdd = $menuService->menuSubItemFromRoute($mAdd, ++$i, $currentRoute, 'Formulaire', 'coreadminz_template_form');
        $mAdd = $menuService->menuSubItemFromRoute($mAdd, ++$i, $currentRoute, 'Liste datagrid', 'coreadminz_template_datagrid');
        $mAdd = $menuService->menuSubItemFromRoute($mAdd, ++$i, $currentRoute, 'Liste datatable', 'coreadminz_template_datatable');
        $menuSubItems[] = $mAdd;
        */


        $mParams = $menuService->menuItemParent('Configuration', 'list');

        $mParams = $menuService->menuSubItemFromRoute($mParams, ++$i, $currentRoute, 'Recipe', 'frontend_recipe_index');

        $mParams = $menuService->menuSubItemFromRoute($mParams, ++$i, $currentRoute, 'Recipe', 'frontend_recipe_index');

        $menuSubItems[] = $mParams;


        return $this->render('frontend/main/sidebar.html.twig', [
            'menu_sub' => $menuService->generer($menuSubItems, $currentRoute),
            'section' => $section,
        ]);
    }
}
