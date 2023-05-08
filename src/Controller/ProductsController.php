<?php 
namespace App\Controller;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ProductsController extends AbstractController
{
    public function __construct(private ProductRepository $repository,
                                private EntityManagerInterface $entityManager )
    {

    }

    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods:'POST')]
    public function lowestPrice(Request $request,
                                int $id,
                                DTOSerializer $serializer,
                                PromotionsFilterInterface $promotionsFilter
    ):Response
    {
        if($request->headers->has('force_fail')){
            return new JsonResponse(
                ['error' => 'Promotions Engine failure message'],
                $request->headers->get('force_fail'),
            );
        }

        //1-deserialize the request content into a DTO
        $lowestPriceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiry::class, 'json');

        //2-filter the deserialized data

        //get the product form id
        $product = $this->repository->find($id); //remember to add error handling

        //add product to DTO object
        $lowestPriceEnquiry->setProduct($product);

        //get the promotions for the product that are valid for the request date
        $promotions = $this->entityManager->getRepository(Promotion::class)->findValidForProduct(
            $product,
            date_create_immutable($lowestPriceEnquiry->getRequestDate())
        );// must handle null value for promotions

        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        //3-serialize the DTO into json Format
        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new Response($responseContent, Response::HTTP_OK, ['Content-Type'=>'application/json']);


    }


    #[Route("/products/{id}", name:"promotions", methods: 'GET')]
    public function promotions(int  $id): Response
    {
       dd($id);
    }
}