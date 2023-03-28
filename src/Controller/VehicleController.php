<?php

namespace App\Controller;

use App\Service\VehicleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('IS_AUTHENTICATED_FULLY', statusCode: 423)]
class VehicleController extends AbstractController
{
  public function __construct(private VehicleService $vehicleService)
  {
  }

  #[Route('vehicles', name: 'list-vehicles', methods: ['GET'])]
  public function index()
  {
    return $this->vehicleService->findAll();
  }

  #[Route('vehicles/{id}', name: 'find-vehicle', methods: ['GET'])]
  public function find(int $id)
  {
    return $this->vehicleService->find($id);
  }

  #[Route('vehicles/fipe/{id}', name: 'find-vehicle-with-fipe', methods: ['GET'])]
  public function findWithFipe(int $id)
  {
    return $this->vehicleService->findWithFipe($id);
  }

  #[Route('vehicles', name: 'create-vehicle', methods: ['POST'])]
  #[IsGranted(attribute: 'ROLE_ADMIN')]
  public function create(Request $request)
  {
    return $this->vehicleService->save($request->request->all());
  }

  #[Route('vehicles/{id}', name: 'update-vehicle', methods: ['PUT', 'PATCH'])]
  #[IsGranted(attribute: 'ROLE_ADMIN')]
  public function update(int $id, Request $request)
  {
    return $this->vehicleService->update($id, $request->request->all());
  }

  #[Route('vehicles/{id}', name: 'remove-vehicle', methods: ['DELETE'])]
  #[IsGranted(attribute: 'ROLE_ADMIN')]
  public function remove(int $id)
  {
    return $this->vehicleService->remove($id);
  }
}
