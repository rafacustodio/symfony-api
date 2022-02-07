<?php

namespace App\Controller;

use App\Form\UserType;
use App\Form\Utils as FormUtils;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private UserRepository $userRepository
    )
    {
    }

    #[Route('/users', name: 'users-show', methods: ['GET', 'HEAD'])]
    public function show(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($users, 'json')
        );
    }

    #[Route('/users/{id}', name: 'users-show-one', methods: ['GET', 'HEAD'])]
    public function showOne(int $id): JsonResponse|Response
    {
        $user = $this->userRepository->findOneById($id);
        if (!$user) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($user, 'json')
        );
    }

    #[Route('/users', name: 'users-create', methods: ['POST'])]
    public function create(Request $request): JsonResponse|Response
    {
        $data = json_decode($request->getContent(), JSON_OBJECT_AS_ARRAY);
        $form = $this->createForm(UserType::class);
        $form->submit($data);
        if (!$form->isValid()) {
            return JsonResponse::fromJsonString(
                $this->serializer->serialize([
                    'errors' => FormUtils::errorsToArray($form->getErrors(true))
                ], 'json'),
                Response::HTTP_BAD_REQUEST
            );
        }
        $user = $this->userRepository->save($form->getData());
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($user, 'json')
        );
    }

    #[Route('/users/{id}', name: 'users-update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), JSON_OBJECT_AS_ARRAY);
        $user = $this->userRepository->findOneById($id);
        if (!$user) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(UserType::class, $user);
        $form->submit($data, false);
        if (!$form->isValid()) {
            return JsonResponse::fromJsonString(
                $this->serializer->serialize([
                    'errors' => FormUtils::errorsToArray($form->getErrors(true))
                ], 'json'),
                Response::HTTP_BAD_REQUEST
            );
        }
        $this->userRepository->save($form->getData());
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($user, 'json')
        );
    }

    #[Route('/users/{id}', name: 'users-del', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $user = $this->userRepository->findOneById($id);
        if (!$user) {
            return new Response('', Response::HTTP_NOT_FOUND);
        }
        $this->userRepository->delete($user);
        return new Response('');
    }
}
