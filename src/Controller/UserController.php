<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'users-show', methods: ['GET', 'HEAD'])]
    public function show(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $users = $userRepository->findAll();
        return JsonResponse::fromJsonString(
            $serializer->serialize($users, 'json'),
            200
        );
    }

    #[Route('/users/{id}', name: 'users-show-one', methods: ['GET', 'HEAD'])]
    public function showOne(
        int $id,
        UserRepository $userRepository,
        SerializerInterface $serializer
    ): JsonResponse|Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        if (!$user) {
            return new Response('', 404);
        }
        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json'),
            200
        );
    }

    #[Route('/users', name: 'users-create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $jsonUser = json_decode($request->getContent(), JSON_OBJECT_AS_ARRAY);
        $user = new User();
        $user->setEmail($jsonUser['email'] ?? '');
        $user->setFirstName($jsonUser['firstName'] ?? '');
        $user->setLastName($jsonUser['lastName'] ?? '');
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return JsonResponse::fromJsonString(
                $serializer->serialize($errors, 'json'),
                400
            );
        }
        $entityManager->persist($user);
        $entityManager->flush();
        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json'),
            200
        );
    }

    #[Route('/users/{id}', name: 'users-update', methods: ['PUT', 'PATCH'])]
    public function update(
        int $id,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $jsonUser = json_decode($request->getContent(), JSON_OBJECT_AS_ARRAY);
        $user = $userRepository->findOneBy(['id' => $id]);
        if (!$user) {
            return new Response('', 404);
        }
        if (isset($jsonUser['email'])) {
            $user->setEmail($jsonUser['email']);
        }
        if (isset($jsonUser['firstName'])) {
            $user->setFirstName($jsonUser['firstName']);
        }
        if (isset($jsonUser['lastName'])) {
            $user->setLastName($jsonUser['lastName']);
        }
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return JsonResponse::fromJsonString(
                $serializer->serialize($errors, 'json'),
                400
            );
        }
        $entityManager->persist($user);
        $entityManager->flush();
        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json'),
            200
        );
    }

    #[Route('/users/{id}', name: 'users-del', methods: ['DEL'])]
    public function delete(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        if (!$user) {
            return new Response('', 404);
        }
        $entityManager->remove($user);
        $entityManager->flush();
        return new Response('', 200);
    }
}
