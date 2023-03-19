<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('users', name: 'ListUsers', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        return $this->json([
            'data' => $userRepository->findAll(),
        ]);
    }

    #[Route('users/{id}', name: 'FindUser', methods: ['GET'])]
    public function find(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);
        if (!$user) throw $this->createNotFoundException();
        return $this->json([
            'data' => $user,
        ]);
    }

    #[Route('users', name: 'CreateUser', methods: ['POST'])]
    public function create(Request $request, UserRepository $userRepository): JsonResponse
    {
        $data =  $request->request->all();
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setCreatedAt(new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));
        $user->setUpdatedAt(new DateTime('now', new DateTimeZone('America/Sao_Paulo')));
        $user->setIsAdmin($data['isAdmin']);
        $userRepository->save($user, true);
        return $this->json([
            'message' => 'User created successfully!',
            'data' => $user,
        ], 201);
    }

    #[Route('users/{id}', name: 'UpdateUser', methods: ['PUT', 'PATCH'])]
    public function update(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        UserRepository $userRepository
    ): JsonResponse {
        $data =  $request->request->all();
        $user = $userRepository->find($id);
        if (!$user) throw $this->createNotFoundException();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setUpdatedAt(new DateTime('now', new DateTimeZone('America/Sao_Paulo')));
        $user->setIsAdmin($data['isAdmin']);
        $doctrine->getManager()->flush();
        return $this->json([
            'message' => 'User updated successfully!',
            'data' => $user,
        ], 201);
    }
}
