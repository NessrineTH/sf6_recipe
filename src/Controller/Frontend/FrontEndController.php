<?php

namespace App\Controller\Frontend;

use App\Controller\CommonBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontEndController extends CommonBaseController
{
    
    #[Route('/', name: 'frontend_index')]
    public function index(Request $request): Response
    {
    
        return $this->render('frontend/index.html.twig');
    }
 

}
