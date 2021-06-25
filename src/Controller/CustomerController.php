<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;


class CustomerController extends AbstractController
{
    /**
     * Recupère la liste des clients
     *
     * @Route("/customer", name="customer_list", methods={"GET"})
     */
    public function list(CustomerRepository $customerRepository, CacheInterface $cache){
        $list = $cache->get('list', function(ItemInterface $item) use ($customerRepository):array{
            $item->expiresAfter(3600);
            return $customerRepository->findAll();
        });

        return $this->json($list, 200, [], ['groups' => 'get:list']);
    }


    /**
     * Recupère les détails d'un client
     *
     * @param Customer $customer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/customer/{id}", name="customer_detail", methods={"GET"})
     */
    public function customer(?Customer $customer){
        if($customer === null){
            return $this->json([
                'status' => 404,
                'message' => 'Customer not found'
            ], 404);
        }
        return $this->json($customer, 200, [], ['groups' => 'get:detail']);
    }

    /**
     * Permet d'ajouter un nouveau client
     *
     * @Route("/customer/add", name="customer_post", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager,
    ValidatorInterface $validator, UserPasswordEncoderInterface $encoder){
        try {
            $post = $serializer->deserialize($request->getContent(), Customer::class, 'json');

            $post->setPassword($encoder);
            $post->setCreatedAt(new \DateTime());

            $errors = $validator->validate($post);

            if(count($errors) > 0){
                return $this->json($errors, 400);
            }

            $entityManager->persist($post);
            $entityManager->flush();

            return $this->json($post, 201, [], ['groups' => 'get:detail']);
        }
        catch (NotEncodableValueException $exception){
            return $this->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * Permet de savoir la liste des utilisateurs lié à un client
     *
     * @param Customer $customer
     * @param UserRepository $userRepository
     * @Route("/customer/{id}/list", name="customer_user", methods={"GET"})
     */
    public function userList(Customer $customer, UserRepository $userRepository, PaginatorInterface $paginator, Request $request, CacheInterface $cache){
        $users = $cache->get('userList', function(ItemInterface $item) use ($userRepository,$customer):array{
            $item->expiresAfter(3600);
            return $userRepository->findBy(['customerId'=>$customer->getId()]);
        });

        $users = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->json($users, 200, [], ['groups'=>'get:userList']);
    }
}
