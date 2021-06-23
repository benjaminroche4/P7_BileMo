<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function list(ProductRepository $productRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $product = $productRepository->findAll();

        $product = $paginator->paginate(
            $product,
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->json($product, 200, [], ['groups' => 'get:list']);
    }

    /**
     * Recupère les détails d'un produits
     *
     * @param Product $product
     * @Route("/product/{id}", name="product_detail", methods={"GET"})
     */
    public function detail(?Product $product)
    {
        if($product === null){
            return $this->json([
                'status' => 404,
                'message' => 'Product not found'
            ], 404);
        }
        return $this->json($product, 200, []);
    }
}
