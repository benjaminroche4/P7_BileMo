<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
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


class CustomerController extends AbstractController
{
    /**
     * Recupère la liste des clients
     *
     * @Route("/customer", name="customer_list", methods={"GET"})
     */
    public function list(CustomerRepository $customerRepository){
        return $this->json($customerRepository->findAll(), 200, [], ['groups' => 'get:list']);
    }

    /**
     * Recupère les détails d'un client
     *
     * @param Customer $customer
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/customer/{id}", name="customer_detail", methods={"GET"})
     */
    public function customer(Customer $customer){
        return $this->json($customer, 200, [], ['groups' => 'get:detail']);
    }

    /**
     * Permet d'ajouter un nouveau client
     *
     * @Route("/customer/add", name="customer_post", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager,
    ValidatorInterface $validator){
        try {
            $post = $serializer->deserialize($request->getContent(), Customer::class, 'json');

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

    public function userCustomer(){

    }

}
