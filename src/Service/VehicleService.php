<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Factory\JsonResponseFactory;
use App\Factory\ClassValidatorFactory;
use App\Repository\VehicleRepository;
use Doctrine\Persistence\ManagerRegistry;
use ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;

class VehicleService
{
  public function __construct(
    private VehicleRepository $vehicleRepository,
    private ManagerRegistry $managerRegistry,
    private JsonResponseFactory $jsonResponseFactory,
    private ClassValidatorFactory $validator,
  ) {
  }

  public function findAll(): JsonResponse
  {
    return $this->json($this->vehicleRepository->findAll());
  }

  public function find(int $id): JsonResponse
  {
    $vehicle = $this->vehicleRepository->find($id);
    if (!$vehicle instanceof Vehicle)
      throw new ErrorException('Vehicle not found', 404);
    return  $this->json($this->vehicleRepository->findAll());
  }

  public function save(array $data): JsonResponse
  {
    $vehicle = new Vehicle();
    $vehicle->setName($data['name']);
    $vehicle->setName($data['brand']);
    $vehicle->setName($data['year']);
    $vehicle->setName($data['fuel']);
    $vehicle->setName($data['price']);
    $this->vehicleRepository->save($vehicle, true);
    return  $this->json($vehicle);
  }

  public function update(int $id, array $data): JsonResponse
  {
    $vehicle = $this->vehicleRepository->find($id);
    if (!$vehicle instanceof Vehicle)
      throw new ErrorException('Vehicle not found', 404);
    $vehicle->setName($data['name']);
    $vehicle->setName($data['brand']);
    $vehicle->setName($data['year']);
    $vehicle->setName($data['fuel']);
    $vehicle->setName($data['price']);
    $this->managerRegistry->getManager()->flush();
    return $this->json($vehicle);
  }

  public function remove(int $id): JsonResponse
  {
    $vehicle = $this->vehicleRepository->find($id);
    if (!$vehicle instanceof Vehicle)
      throw new ErrorException('Vehicle not found', 404);
    $this->vehicleRepository->remove($vehicle, true);
    return $this->json($vehicle);
  }

  public function json($array, $status = 200): JsonResponse
  {
    return $this->jsonResponseFactory->create($array, $status);
  }
}
