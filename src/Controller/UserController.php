<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
  public function __construct(private UserService $userService)
  {
  }

  #[Route('users', name: 'ListUsers', methods: ['GET'])]
  // #[IsGranted(attribute: 'ROLE_ADMIN')]
  public function index()
  {
    return $this->userService->findAll();
  }

  #[Route('users/{id}', name: 'FindUser', methods: ['GET'])]
  public function find(int $id)
  {
    return $this->userService->find($id);
  }

  #[Route('users', name: 'CreateUser', methods: ['POST'])]
  public function create(Request $request)
  {
    return $this->userService->save($request->request->all());
  }

  #[Route('users/{id}', name: 'UpdateUser', methods: ['PUT', 'PATCH'])]
  public function update(int $id, Request $request)
  {
    return $this->userService->update($id, $request->request->all());
  }

  #[Route('users/{id}', name: 'RemoveUser', methods: ['DELETE'])]
  public function remove(int $id)
  {
    return $this->userService->remove($id);
  }
}
