<?php

namespace App\Filter;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class LowestPriceFilter implements PromotionsFilterInterface
{

    public function apply(PromotionEnquiryInterface $enquiry, Promotion ...$promotion)
    : PromotionEnquiryInterface
    {
        $price = $enquiry->getProduct()->getPrice();
        $quantity = $enquiry->getQuantity();
        $lowestPrice = $quantity*$price;

        //loop over promotions' modification logic against the enquiry
        //1- check does the promotion apply
        //2- apply the price modification to obtain a modified price (how)


        //$modifiedPrice = $priceModifier->modify($price, $quantity, $promotion, $enquiry);


        //3- check if $modifiedPrice < lowestProce
            //1-save the enquiry properties
            //2-update $lowestProice


        $enquiry->setDiscountedPrice(250);
        $enquiry->setPrice(100);
        $enquiry->setPromotionId(3);
        $enquiry->setPromotionName('Black friday half price');

        return $enquiry;
    }
}