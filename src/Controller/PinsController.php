<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\User;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\security;

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
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo): Response
    {
        if(! $this->getUser())
        {
            // $this->addFlash('error', 'you need to log in first');
            // return $this->redirectToRoute('app_login');
            $this->denyAccessUnlessGranted('ROLE_USER');
        }

        if(! $this->getUser()->isVerified())
        //attention l'ordre des vérifications est important
        {
            $this->addFlash('error', 'you need to have a verified account');
            return $this->redirectToRoute('app_home');
        }


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
            // $thao = $userRepo->findOneBy(['email' => 'thaogarban@gmail.com']);
            // $pin->setuser($thao);

            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin successfully created');

            return $this->redirectToRoute('app_home');
        }
        
        return $this->render('pins/create.html.twig', ['form' =>$form->createView()]);
    }

    //EDIT
    #[Route('/pins/{id}/edit', name: 'app_pins_edit', methods: ['GET', 'PUT', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, PinRepository $pinRepository, int $id): Response
    {
        $user = $this->getUser();
    
        if (!$user) {
            $this->addFlash('error', 'You need to log in first');
            return $this->redirectToRoute('app_login');
        }
    
        if (!$user->isVerified()) {
            $this->addFlash('error', 'You need to have a verified account');
            return $this->redirectToRoute('app_home');
        }
    
        $pin = $pinRepository->find($id);
    
        if (!$pin) {
            throw $this->createNotFoundException('Pin not found');
        }
    
        if ($pin->getUser() !== $user) {
            $this->addFlash('error', 'Access forbidden');
            return $this->redirectToRoute('app_home');
        }
    
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Pin successfully updated');
            return $this->redirectToRoute('app_home');
        }
    
        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'form' => $form->createView()
        ]);
    }


    //DELETE
    #[Route('/pins/{id}', name: 'app_pins_delete', methods: ['DELETE','POST'])]
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em, int $id, PinRepository $pinRepository, CsrfTokenManagerInterface $csrfTokenManager): Response
    {

        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', 'You need to log in first');
            return $this->redirectToRoute('app_login');
        }
    
        if (!$user->isVerified()) {
            $this->addFlash('error', 'You need to have a verified account');
            return $this->redirectToRoute('app_home');
        }
        $pin = $pinRepository->find($id);

        if (!$pin) {
            throw $this->createNotFoundException('Pin not found');
        }

        if (!$user->isUserAllowedToDelete($pin)) {
            $this->addFlash('error', 'Access forbidden');
            return $this->redirectToRoute('app_home');
        }

        // if(! $this->getUser())
        // {
        //     $this->addFlash('error', 'you need to log in first');
        //     return $this->redirectToRoute('app_login');
        // }

        // if(! $this->getUser()->isVerified())
        // {
        //     $this->addFlash('error', 'you need to have a verified account');
        //     return $this->redirectToRoute('app_home');
        // }

        // if($pin->getUser() != $this->getUser())
        // {
        //     $this->addFlash('error', 'Acces forbidden');
        //     return $this->redirectToRoute('app_home');
        // }

        // $pin = $pinRepository->find($id);


        // if (!$pin) {
        //     throw $this->createNotFoundException('Pin not found');
        // }
    
        // if (!$this->isUserAllowedToDelete($user, $pin)) {
        //     $this->addFlash('error', 'Access forbidden');
        //     return $this->redirectToRoute('app_home');
        // }

        $em->remove($pin);
        $em->flush();
        $this->addFlash('info', 'Pin successfully deleted');


        return $this->redirectToRoute('app_home');
    }

    // /**
    //  * @Route{"/pins{id<(0-9)->}", name="app_pins_delete", methods=("DELETE")}
    //  */
    // public function delete(Request $request, Pin $pin, EntityManagerInterface $em):Response
    // {
    //     if($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $request->get('csrf_token'))) {
    //         $em->remove($pin);
    //         $em->flush();

    //         $this->addFlash('info', 'Pin successfully deleted');
    //     }
    
    //     return $this->redirectToRoute('app_home');
    // }
}
