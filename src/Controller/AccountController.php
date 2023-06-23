<?php

namespace App\Controller;

// 
use App\Entity\User;
use App\Form\UserFormType;
use Imagine\Image\Profile;
use App\Service\FileUploader;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route('/account')]

class AccountController extends AbstractController
{
    #[Route('/', name: 'app_account')]
    public function show(): Response
    {
        // if(! $this->getUser())
        // {
        //     $this->addFlash('error', 'you need to log in first');
        //     return $this->redirectToRoute('app_login');
        // }

        return $this->render('account/show.html.twig');
    }

    #[Route('/edit', name: 'app_account_edit', methods:['GET','POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    // public function edit(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        // if(! $this->getUser())
        // {
        //     $this->addFlash('error', 'you need to log in first');
        //     return $this->redirectToRoute('app_login');
        // }

        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class,$user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            // $profileImageFile = $form['profileImage']->getData();
            // if ($profileImageFile) {
            //     // $targetDirectory = $this->getParameter('app.file_upload_directory');
            //     $newPI = $fileUploader->upload($profileImageFile);
            //     $user->setProfileImage($newPI);
            // }
            $em->flush();

            $this->addFlash('success', 'Account updated successfully');
            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/edit.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    #[Route('/change-password', name: 'app_account_change_password', methods:['GET','POST'])]
public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
{
    // if(! $this->getUser())
    // {
    //     $this->addFlash('error', 'you need to log in first');
    //     return $this->redirectToRoute('app_login');
    // }

    $user = $this->getUser();
    // dd($user);
    $form = $this->createForm(ChangePasswordFormType::class, null, [
        'current_password_is_required'=>true
    ]);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        $user->setPassword(
        $passwordHasher->hashPassword($user, $form->get('newPassword')->getData())
        );
        $em->flush();
        $this->addFlash('success', 'Password updated successfully');
        return $this->redirectToRoute('app_account');
    }

    return $this->render('account/change_password.html.twig',[
        'form' => $form->createView()
    ]);
}

}

