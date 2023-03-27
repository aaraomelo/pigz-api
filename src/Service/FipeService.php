<?php

namespace App\Service;

use App\Entity\Fipe;
use App\Factory\JsonResponseFactory;
use App\Factory\ClassValidatorFactory;
use App\Repository\FipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use ErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;

class FipeService
{
  public function __construct(
    private FipeRepository $fipeRepository,
    private ManagerRegistry $managerRegistry,
    private JsonResponseFactory $jsonResponseFactory,
    private ClassValidatorFactory $validator,
  ) {
  }

  public function findAll(): JsonResponse
  {
    return $this->json($this->fipeRepository->findAll());
  }

  public function find(int $id): JsonResponse
  {
    $fipe = $this->fipeRepository->find($id);
    if (!$fipe instanceof Fipe)
      throw new ErrorException('Fipe not found', 404);
    return  $this->json($this->fipeRepository->findAll());
  }

  public function save(array $data): JsonResponse
  {
    $fipe = new Fipe();
    $fipe->setName($data['fipe_code']);
    $fipe->setName($data['name']);
    $fipe->setName($data['brand']);
    $fipe->setName($data['year']);
    $fipe->setName($data['fuel']);
    $fipe->setName($data['price']);
    $this->fipeRepository->save($fipe, true);
    return  $this->json($fipe);
  }

  public function update(int $id, array $data): JsonResponse
  {
    $fipe = $this->fipeRepository->find($id);
    if (!$fipe instanceof Fipe)
      throw new ErrorException('Fipe not found', 404);
    $fipe->setName($data['fipe_code']);
    $fipe->setName($data['name']);
    $fipe->setName($data['brand']);
    $fipe->setName($data['year']);
    $fipe->setName($data['fuel']);
    $fipe->setName($data['price']);
    $this->managerRegistry->getManager()->flush();
    return $this->json($fipe);
  }

  public function remove(int $id): JsonResponse
  {
    $fipe = $this->fipeRepository->find($id);
    if (!$fipe instanceof Fipe)
      throw new ErrorException('Fipe not found', 404);
    $this->fipeRepository->remove($fipe, true);
    return $this->json($fipe);
  }

  public function json($array, $status = 200): JsonResponse
  {
    return $this->jsonResponseFactory->create($array, $status);
  }
}
