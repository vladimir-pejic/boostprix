<?php

namespace App\Controller;

use App\DTO\LowestPriceEnquiryDTO;
use App\Filter\PromotionsFilterInterface;
use App\Service\Serializer\DTOSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        Request $request,
        int $id,
        DTOSerializer $serializer,
        PromotionsFilterInterface $promotionsFilter,
    ): Response
    {
        if ($request->headers->has('force_fail')) {
            return new JsonResponse([
                'error' => 'Boostprix failed. We are so sorry...',
            ], $request->headers->get('force_fail'));
        }

        /** @var LowestPriceEnquiryDTO $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize(
            $request->getContent(), LowestPriceEnquiryDTO::class, 'json'
        );

        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new Response($responseContent, 200);
    }


    #[Route('products/{id}/promotions', name: 'promotions', methods: 'GET')]
    public function promotions()
    {

    }
}
