<?php

namespace App\Controller;

use App\Service\FipeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY', statusCode: 423)]
class FipeController extends AbstractController
{
  public function __construct(private FipeService $fipeService)
  {
  }

  #[Route('fipe', name: 'list-fipe', methods: ['GET'])]
  public function index()
  {
    return $this->fipeService->findAll();
  }

  #[Route('fipe/{id}', name: 'find-fipe', methods: ['GET'])]
  public function find(int $id)
  {
    return $this->fipeService->find($id);
  }

  #[Route('fipe', name: 'create-fipe', methods: ['POST'])]
  #[IsGranted(attribute: 'ROLE_ADMIN')]
  public function create(Request $request)
  {
    return $this->fipeService->save($request->request->all());
  }

  #[Route('fipe/{id}', name: 'update-fipe', methods: ['PUT', 'PATCH'])]
  #[IsGranted(attribute: 'ROLE_ADMIN')]
  public function update(int $id, Request $request)
  {
    return $this->fipeService->update($id, $request->request->all());
  }

  #[Route('fipe/{id}', name: 'remove-fipe', methods: ['DELETE'])]
  #[IsGranted(attribute: 'ROLE_ADMIN')]
  public function remove(int $id)
  {
    return $this->fipeService->remove($id);
  }
}
