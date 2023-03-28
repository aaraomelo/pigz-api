<?php

namespace App\Service;

use App\Entity\Fipe;
use App\Entity\Vehicle;
use App\Factory\JsonResponseFactory;
use App\Factory\ClassValidatorFactory;
use App\Repository\FipeRepository;
use App\Repository\VehicleRepository;
use Doctrine\Persistence\ManagerRegistry;
use ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;

class VehicleService
{
  public function __construct(
    private VehicleRepository $vehicleRepository,
    private FipeRepository $fipeRepository,
    private ManagerRegistry $managerRegistry,
    private JsonResponseFactory $jsonResponseFactory,
    private ClassValidatorFactory $validator,
  ) {
  }

  public function findAll(): JsonResponse
  {
    return $this->json($this->vehicleRepository->findAll());
  }

  public function findWithFipe(int $id): JsonResponse
  {
    $vehicle = $this->vehicleRepository->find($id);

    if (!$vehicle instanceof Vehicle)
      throw new ErrorException('Vehicle not found', 404);

    $fipe = $this->fipeRepository->findOneBy([
      'name' => $vehicle->getName(),
      'brand' => $vehicle->getBrand(),
      'year' => $vehicle->getYear(),
      'fuel' => $vehicle->getFuel(),
    ]);

    if (!$fipe instanceof Fipe)
      throw new ErrorException('Fipe not found', 404);

    $data = [
      'id' => $vehicle->getId(),
      'name' => $vehicle->getName(),
      'brand' => $vehicle->getBrand(),
      'year' => $vehicle->getYear(),
      'fuel' => $vehicle->getFuel(),
      'price' => $vehicle->getPrice(),
      'price_fipe' => $fipe->getPrice(),
      'price_difference' => $vehicle->getPrice() - $fipe->getPrice(),
    ];

    return  $this->json($data);
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
    $vehicle->setBrand($data['brand']);
    $vehicle->setYear($data['year']);
    $vehicle->setFuel($data['fuel']);
    $vehicle->setPrice($data['price']);
    $this->vehicleRepository->save($vehicle, true);
    return  $this->json($vehicle);
  }

  public function update(int $id, array $data): JsonResponse
  {
    $vehicle = $this->vehicleRepository->find($id);
    if (!$vehicle instanceof Vehicle)
      throw new ErrorException('Vehicle not found', 404);
    $vehicle->setName($data['name']);
    $vehicle->setBrand($data['brand']);
    $vehicle->setYear($data['year']);
    $vehicle->setFuel($data['fuel']);
    $vehicle->setPrice($data['price']);
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
