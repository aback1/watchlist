<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    #[Route('/movies/post', name: 'post_movies', methods: ['POST'])]
    public function postMovies(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
    ) : JsonResponse {

        //Check if all the required fields are set in the request object.
        $data = json_decode($request->getContent(), true);
        if(!$data || !isset($data['Title']) || !isset($data['username']) || !isset($data['Year'])
        || !isset($data['imdbID']) || !isset($data['Poster']) || !isset($data['Type'])){
            return new JsonResponse(["error => Missing required fields"], 400);
        }
        $movie = new Movie();

        $movie->setTitle($data['Title']);
        $movie->setUsername($data['username']);
        $movie->setYear($data['Year']);
        $movie->setImdbID($data['imdbID']);
        $movie->setPoster($data['Poster']);
        $movie->setType($data['Type']);

        $errors = $validator->validate($movie);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(["error => Missing required fields"], 400);
        }

        $entityManager->persist($movie);
        $entityManager->flush();

        return new JsonResponse(["status" => "successfully added item to watchlist"]);

    }

    #[Route('/movies/get', name: 'get_movies', methods: ['GET'])]
    public function getMovies(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $username = $request->query->get('username');
        $repository = $entityManager->getRepository(Movie::class);

        if($username){
            $moviesList = $repository->findBy(['username' => $username]);
        } else {
            $moviesList = $repository->findAll();
        }

        //only get the data where the username is equal to the username in the request.
        $data = [];
        foreach($moviesList as $movie){
            $data[] = [
            'username' => $movie->getUsername(),
            'Title' => $movie->getTitle(),
            'Year' => $movie->getYear(),
            'imdbID' => $movie->getImdbID(),
            'Poster' => $movie->getPoster(),
            'Type' => $movie->getType(),
             'Rating' => $movie->getRating(),
             'Watched'=> $movie->getWatched()

            ];

        }
        return new JsonResponse($data, 200);
    }
    #[Route('/movies/delete', name: 'delete_movies', methods: ['DELETE'])]
    public function deleteMovies(
        Request $request,
        EntityManagerInterface $entityManager,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['username']) || !isset($data['imdbID'])|| !isset($data['Title']))
         {
             return new JsonResponse(["error => the title imdbID and username are needed to delete the entry from the watchList!"], 400);
         }

        $movie = $entityManager->getRepository(Movie::class)->findOneBy([
            'username' => $data['username'],
            'imdbID' => $data['imdbID'],
            'Title' => $data['Title']
        ]);

        if(!$movie){
            return new JsonResponse(["error => entry not found in the watch list!"], 404);
    }
        $entityManager->remove($movie);
        $entityManager->flush();

        return new JsonResponse(["status" => "Entry deleted successfully from the watchlist"], 200);
    }

    #[Route('/movies/rate', name: 'rate_movie', methods: ['PATCH'])]
    public function rateMovie(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['username'], $data['imdbID'], $data['rating'])) {
            return new JsonResponse(["error" => "Missing required fields"], 400);
        }

        $movie = $entityManager->getRepository(Movie::class)->findOneBy([
            'username' => $data['username'],
            'imdbID' => $data['imdbID']
        ]);

        if (!$movie) {
            return new JsonResponse(["error" => "Movie not found in watchlist"], 404);
        }

        $movie->setRating((float)$data['rating']);

        $errors = $validator->validate($movie);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(["errors" => $errorMessages], 400);
        }

        $entityManager->flush();

        return new JsonResponse(["status" => "Rating updated successfully"], 200);
    }

    #[Route('/movies/watched', name: 'mark_movie_watched', methods: ['PATCH'])]
    public function markMovieWatched(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['username'], $data['imdbID'])) {
            return new JsonResponse(["error" => "Missing required fields"], 400);
        }

        $movie = $entityManager->getRepository(Movie::class)->findOneBy([
            'username' => $data['username'],
            'imdbID' => $data['imdbID']
        ]);

        if (!$movie) {
            return new JsonResponse(["error" => "Movie not found in watchlist"], 404);
        }

        $movie->setWatched(true);
        $entityManager->flush();

        return new JsonResponse(["status" => "Movie marked as watched"], 200);
    }

}
