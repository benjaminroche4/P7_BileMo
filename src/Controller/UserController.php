<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{

    /**
     * Recupère les détails d'un client et de savoir à qui il est affilié
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/user/{id}", name="user_detail", methods={"GET"}, options={"default" : null})
     */
    public function user(?User $user){
        if($user === null){
            return $this->json([
                'status' => 404,
                'message' => 'User not found'
            ], 404);
        }
        return $this->json($user, 200, [], ['groups' => 'get:infos']);
    }

    /**
     * Permet de supprimer un utilisateur
     *
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @Route("/user/delete/{id}", name="user_delete", methods={"delete"})
     */
    public function delete(?User $user, EntityManagerInterface $entityManager, UserRepository $repository){
        $user = $repository->find($user->getId());
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json('The user has been delete', 204, []);
    }

    /**
     * Permet d'ajouter un nouvel utilisateur
     *
     * @Route("/user/add", name="user_add", methods={"post"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager,
    SerializerInterface $serializer, ValidatorInterface $validator, CustomerRepository $customerRepository){
        try{
            $post = $serializer->deserialize($request->getContent(), User::class, 'json');
            $post->setCreatedAt(new \DateTime());
            $post->setCustomerId($customerRepository->find('3809'));

            $errors = $validator->validate($post);

            if(count($errors) > 0){
                return $this->json($errors, 400);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->json($post, 201, [], ['groups' => 'post:user']);
        }
        catch(NotEncodableValueException $exception){
            return $this->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * Permet de modifier les infos d'un client
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param User $user
     * @return JsonResponse
     * @Route("/user/update/{id}", name="user_update", methods={"put"})
     */
    public function update(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager,
    ValidatorInterface $validator, User $user, UserRepository $repository){
        try{
            $user = $repository->find($user->getId());

            $post = $serializer->deserialize($request->getContent(), User::class, 'json');
            $post->setUpdatedAt(new \DateTime());

            $errors = $validator->validate($post);

            if(count($errors) > 0){
                return $this->json($errors, 400);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->json($post, 201, []);
        }
        catch (NotEncodableValueException $exception){
            return $this->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ], 400);
        }

    }

}
