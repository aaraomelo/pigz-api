<?php

namespace App\Service;

use App\Entity\User;
use App\Factory\JsonResponseFactory;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Persistence\ManagerRegistry;
use ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
  public function __construct(
    private UserRepository $userRepository,
    private ManagerRegistry $managerRegistry,
    private UserPasswordHasherInterface $passwordHasher,
    private JsonResponseFactory $jsonResponseFactory,
  ) {
  }

  public function json($array): JsonResponse
  {
    return $this->jsonResponseFactory->create($array);
  }

  public function findAll(): JsonResponse
  {
    return $this->json($this->userRepository->findAll());
  }

  public function find(int $id): JsonResponse
  {
    $user = $this->userRepository->find($id);
    if (!$user instanceof User)
      throw new ErrorException('User not found', 404);
    return  $this->json($this->userRepository->findAll());
  }

  public function save(array $data): JsonResponse
  {
    $user = $this->userRepository->findOneBy(['email' => $data['email']]);
    if ($user instanceof User)
      throw new ErrorException('Email already exists', 409);
    $user = new User();
    $hashedPassword = $this->passwordHasher->hashPassword(
      $user,
      $data['password']
    );
    $user->setName($data['name']);
    $user->setEmail($data['email']);
    $user->setPassword($hashedPassword);
    $user->setCreatedAt(new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));
    $user->setUpdatedAt(new DateTime('now', new DateTimeZone('America/Sao_Paulo')));
    $user->setRoles($data['roles']);
    $this->userRepository->save($user, true);
    return  $this->json($user);
  }

  public function update(int $id, array $data): JsonResponse
  {
    $user = $this->userRepository->find($id);
    if (!$user instanceof User)
      throw new ErrorException('User not found', 404);
    $user->setName($data['name']);
    $user->setUpdatedAt(new DateTime('now', new DateTimeZone('America/Sao_Paulo')));
    $this->managerRegistry->getManager()->flush();
    return $this->json($user);
  }

  public function remove(int $id): JsonResponse
  {
    $user = $this->userRepository->find($id);
    if (!$user instanceof User)
      throw new ErrorException('User not found', 404);
    $this->userRepository->remove($user, true);
    return $this->json($user);
  }
}
