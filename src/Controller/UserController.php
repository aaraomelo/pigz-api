<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
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
}
