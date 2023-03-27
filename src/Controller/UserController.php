<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    #[Route('users', name: 'ListUsers', methods: ['GET'])]
    #[IsGranted(attribute: 'ROLE_ADMIN')]
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
        if (!$user instanceof \App\Entity\User) throw $this->createNotFoundException();
        return $this->json([
            'data' => $user,
        ]);
    }

    #[Route('users', name: 'CreateUser', methods: ['POST'])]
    public function create(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = $request->request->all();
        $user = new User();
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data['password']
        );
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($hashedPassword);
        $user->setCreatedAt(new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));
        $user->setUpdatedAt(new DateTime('now', new DateTimeZone('America/Sao_Paulo')));
        $user->setRoles($data['roles']);
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
        $user = $userRepository->find($id);
        if (!$user instanceof \App\Entity\User) throw $this->createNotFoundException();
        $data = $request->request->all();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setUpdatedAt(new DateTime('now', new DateTimeZone('America/Sao_Paulo')));
        $doctrine->getManager()->flush();
        return $this->json([
            'message' => 'User updated successfully!',
            'data' => $user,
        ], 201);
    }


    #[Route('users/{id}', name: 'RemoveUser', methods: ['DELETE'])]
    public function remove(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);
        if (!$user instanceof \App\Entity\User) throw $this->createNotFoundException();
        $userRepository->remove($user, true);
        return $this->json('', 204);
    }
}
