<?php

namespace App\Controller\Frontend;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recipe', name: 'frontend_recipe_')]
class RecipeController extends BaseController
{

    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $recipes = $this->getRecipes();

        $viexParams = [
            'recipes' => $recipes
        ];

        return $this->render('frontend/recipe/index.html.twig', $viexParams);
    }

    #[Route('/detail/{nom}', name: 'detail')]
    public function detail(Request $request, string $nom): Response
    {
        $recipes = $this->getRecipes();
        $recipe = null;
        foreach ($recipes as $rec) {
            if ($rec['nom'] === $nom) {
                $recipe = $rec;
                break;
            }
        }

        $viexParams = [
            'recipe' => $recipe
        ];

        return $this->render('frontend/recipe/detail.html.twig', $viexParams);

    }
}
