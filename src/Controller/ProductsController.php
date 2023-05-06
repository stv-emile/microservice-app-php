<?php 
namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Filter\PromotionsFilterInterface;
use App\Service\Serializer\DTOSerializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductsController extends AbstractController
{
    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods:'POST')]
    public function lowestPrice(Request $request,
                                int $id,
                                PromotionsFilterInterface $promotionsFilter,
                                DTOSerializer $serializer):Response
    {
        if($request->headers->has('force_fail')){
            return new JsonResponse(
                ['error' => 'Promotions Engine failure message'],
                $request->headers->get('force_fail'),
            );
        }

        //deserialize the request content into a DTO
        $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');

        //filter the deserialized data
        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry);

        //serialize the DTO into json Format
        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new Response($responseContent, Response::HTTP_OK);



    }


    #[Route("/products/{id}", name:"promotions", methods: 'GET')]
    public function promotions(int  $id): Response
    {
       dd($id);
    }
}