<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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


    //CREATE
    #[Route('/pins/create', name: 'app_pins_create', methods: ['GET', 'POST'], priority: 10)]
    // #[Route('/pins/create', name: 'app_pins_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        
        // // version 1
        // $form = $this->createFormBuilder()
        //         ->add('title', TextType::class)
        //         ->add('description', TextareaType::class)
        //         ->getForm()
        // ;

        // $form->handleRequest($request);
        // //formulaire récupère les données du formulaire via la requête

        // if($form->isSubmitted() && $form->isValid())
        // {
        //     $data = $form->getData();
        //     $pin = new Pin;
        //     $pin->setTitle($data['title']);
        //     $pin->setDescription($data['description']);


        // //version 2
        // $form = $this->createFormBuilder(new Pin)
        //     ->add('title', TextType::class)
        //     ->add('description', TextareaType::class)
        //     ->getForm()
        // ;

        // $form->handleRequest($request);

        // if($form->isSubmitted() && $form->isValid())
        // {
        //     $pin = $form->getData();


        //  // version 3
        // $pin = new Pin;
        // $form = $this->createFormBuilder($pin, [
        //     'auto_initialize' => true,])
        //     ->add('title')
        //     ->add('description')
        //     ->getForm()
        // ;

        // $form->handleRequest($request);

        // if($form->isSubmitted() && $form->isValid())
        // {

        // version 4
        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);//on passe par le form qu'on a crée avec symfony console make:form

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin successfully created');

            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('pins/create.html.twig', ['form' =>$form->createView()]);
    }

    //EDIT
    #[Route('/pins/{id}/edit', name: 'app_pins_edit', methods: ['GET' ,'PUT', 'POST'])]
    
    public function edit(Request $request, EntityManagerInterface $em, int $id, PinRepository $pinRepository):Response
    {
        $pin = $pinRepository->find($id);

        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // $em->persist($pin);
            $em->flush();
            $this->addFlash('success', 'Pin successfully updated');


            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }








}
