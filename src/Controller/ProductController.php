<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    /**
     * Récupère la liste des produits
     *
     * @Route("/product", name="product_list", methods={"GET"})
     */
    public function list(ProductRepository $productRepository): Response
    {
        return $this->json($productRepository->findAll(), 200, [], ['groups' => 'get:list']);
    }

    /**
     * Recupère les détails d'un produits
     *
     * @param Product $product
     * @Route("/product/{id}", name="product_detail", methods={"GET"})
     */
    public function detail(Product $product)
    {
        return $this->json($product, 200, []);
    }
}
