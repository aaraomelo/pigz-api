<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseFactory
{
  public function __construct(private SerializerInterface $serializer)
  {
  }

  public function create($data, int $status = 200, array $headers = []): JsonResponse
  {
    return new JsonResponse(
      $this->serializer->serialize([
        'status' => $status,
        'data' => $data,
      ], JsonEncoder::FORMAT),
      $status,
      array_merge($headers, [
        'Content-Type' => 'application/json;charset=UTF-8'
      ]),
      true
    );
  }
}
