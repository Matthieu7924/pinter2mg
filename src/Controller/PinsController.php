<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    #[Route('/pins/{id}', name: 'app_pins_show', methods: ['GET'])]
    public function show(int $id, PinRepository $pinRepository): Response
    {
        $pin = $pinRepository->find($id);

        if (!$pin) {
            throw $this->createNotFoundException('Pin not found');
        }

        return $this->render('pins/show.html.twig', compact('pin'));
    }


}
