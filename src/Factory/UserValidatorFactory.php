<?php

namespace App\Factory;

use App\Entity\User;
use ErrorException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserValidatorFactory
{
  public function __construct(
    private SerializerInterface $serializer,
    private ValidatorInterface $validator,
  ) {
  }

  public function validate(User $user)
  {
    $errors = $this->validator->validate($user);
    if (count($errors) > 0) {
      $array = json_decode(
        $this->serializer->serialize(
          $errors,
          JsonEncoder::FORMAT
        ),
        true
      );
      $data = [
        'title' => $array['title'],
        'violations' => [],
      ];
      foreach ($array['violations'] as $violation)
        $data['violations'][$violation['propertyPath']]  = $violation['title'];
      throw new ErrorException(json_encode($data), 422);
    }
  }
}
