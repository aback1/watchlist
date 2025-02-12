<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'registration', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        userPasswordHasherInterface $passwordHasher,
        validatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if(!$data || !isset($data["name"]) || !isset($data["password"])) {
            return new JsonResponse(["error" => "Missing required parameters: name and password"], 400);
        }

        $user = new User();
        //Rfc4122 to make it a valid string
        $user->setId(Uuid::v4()->toRfc4122());
        $user->setName($data["name"]);

        $hashedPassword = $passwordHasher->hashPassword($user, $data["password"]);
        $user->setPassword($hashedPassword);

        //validate Errors due to the user entity first before returning the response.
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }


        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(["status" => "User created successfully"], 201);
    }

}