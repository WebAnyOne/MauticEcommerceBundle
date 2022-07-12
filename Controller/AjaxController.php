<?php

namespace MauticPlugin\MauticEcommerceBundle\Controller;

use MauticPlugin\MauticEcommerceBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends AbstractController
{
    public function executeAjaxAction(string $action, Request $request, string $bundle): JsonResponse
    {
        $productRepository = $this->get('mautic_ecommerce.repository.product');
        $term = $request->query->get('filter');

        $products = $productRepository->search($term);

        return new JsonResponse(
            array_merge(
                ['success' => true],
                array_map(static function (Product $product): array {
                    return ['value' => $product->getName(), 'id' => $product->getId()];
                }, $products)
            )
        );
    }
}
