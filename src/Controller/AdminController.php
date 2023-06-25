<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


#[Route('/admin')]
class AdminController extends AbstractController
{

    private $entityManager;
    private $doctrine;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $doctrine)
    {
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
    }


    // #[Route('/', name: 'app_admin_index')]
    // public function index(): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_USER');
    //     return $this->render('admin/index.html.twig');
    // }

    #[Route('/pins', name: 'app_admin_pins')]
    public function pinsIndex(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('admin/pins.html.twig');
    }

    #[Route('/', name: 'app_admin_index')]
    public function userList(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $userRepository = $this->entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}/edit', name: 'app_admin_user_edit')]
    public function editUser(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Gérer l'édition de l'utilisateur ici

        return $this->render('admin/edit_user.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/users/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function deleteUser(int $id, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        // Gérer la suppression de l'utilisateur ici

        return $this->redirectToRoute('app_admin_index');
    }
    
}
