<?php

namespace App\Controller;

use App\Entity\Spendings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class SpendingsController extends AbstractController
{
    #[Route('/spendings/post', name: 'post_spendings', methods: ['POST'])]
    public function postSpending(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse
    {
        //Check if all the required fields are entered in the request object.
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data["month"]) || !isset($data["income"]) || !isset($data["foodanddrinkscosts"]) || !isset($data["sidecosts"])) {
            return new JsonResponse(["error" => "Missing required fields"], 400);
        }
        $spendings = new Spendings();


        $spendings->setUsername($data["username"]);
        $spendings->setMonth($data["month"]);
        $spendings->setIncome($data["income"]);
        $spendings->setFoodAndDrinkcosts($data["foodanddrinkscosts"]);
        $spendings->setRentcosts($data["rentcosts"]);
        $spendings->setSidecosts($data["sidecosts"]);
        $spendings->setInsurancecosts($data["insurancecosts"]);
        $spendings->setSavingscosts($data["savingscosts"]);
        $spendings->setHobbycosts($data["hobbycosts"]);
        $spendings->setMobilitycosts($data["mobilitycosts"]);


        $errors = $validator->validate($spendings);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(["error" => $errorMessages], 400);
        }

        $entityManager->persist($spendings);
        $entityManager->flush();

        return new JsonResponse(["status" => "monthly spendings posted successfully"], 201);
    }
    #[Route('/spendings/get', name: 'get_spendings', methods: ['GET'])]
    public function getSpendings(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        $username = $request->query->get('username');
        $repository = $entityManager->getRepository(Spendings::class);

        if($username) {
            $spendingsList = $repository->findBy(['username' => $username]);
        } else {
            $spendingsList = $repository->findAll();
        }


        $data = [];
        foreach ($spendingsList as $spending) {
            $data[] = [
                'username' => $spending->getUsername(),
                'month' => $spending->getMonth(),
                'income' => $spending->getIncome(),
                "foodanddrinkscosts" => $spending->getFoodAndDrinkcosts(),
                "rentcosts" => $spending->getRentcosts(),
                "sidecosts" => $spending->getSidecosts(),
                "insurancecosts" => $spending->getInsurancecosts(),
                "mobilitycosts" => $spending->getMobilitycosts(),
                "hobbycosts" => $spending->getHobbycosts(),
                "savingscosts" => $spending->getSavingscosts(),
            ];
        }
        return new JsonResponse($data, 200);
    }
    #[Route('spendings/delete', name: 'delete_spendings', methods: ['DELETE'])]
    public function deleteSpending(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // Decode the JSON request body
        $data = json_decode($request->getContent(), true);



        // Check if the required 'id' is provided
        if (!isset($data['month']) || !isset($data['username'])) {
            return new JsonResponse(["error" => "Missing spending username or month"], 400);
        }

        $spending = $entityManager->getRepository(Spendings::class)->findOneBy([
            "username" => $data["username"],
            "month" => $data["month"]
        ]);

        if (!$spending) {
            return new JsonResponse(["error" => "Spending record not found"], 404);
        }

        $entityManager->remove($spending);
        $entityManager->flush();

        return new JsonResponse(["status" => "Spending record deleted successfully"], 200);
    }

}

