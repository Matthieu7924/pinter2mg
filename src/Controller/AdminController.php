<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('admin/index.html.twig');
    }

    #[Route('/pins', name: 'app_admin_pins')]
    public function pinsIndex(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('admin/pins.html.twig');
    }
}
