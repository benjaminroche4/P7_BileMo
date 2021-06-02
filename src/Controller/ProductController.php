<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    /**
     * Permet de récuperer la liste des produits en BDD et d'envoyer le résultat aux clients
     *
     * @Route("/product", name="product", methods={"GET"})
     */
    public function productList(ProductRepository $productRepository, SerializerInterface $serializer): Response
    {
        return $this->json($productRepository->findAll(), 200, [], ['groups' => 'get:list']);
    }

    /**
     * Permet d'afficher les détails d'un produit et d'envoyer le résultat aux clients
     * @param Product $product
     * @Route("/product/{id}", name="detail", methods={"GET"})
     */
    public function productDetail(Product $product, SerializerInterface $serializer){
        return $this->json($product, 200, []);
    }
}
