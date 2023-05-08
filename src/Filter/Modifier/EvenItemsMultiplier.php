<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class EvenItemsMultiplier implements PriceModifierInterface
{

    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {

        if(!($enquiry->getQuantity() >= $promotion->getCriteria()["minimum_quantity"])){
            return $price*$quantity;
        }

        //get the odd item if there is one
        $oddCount = $quantity % 2;

        //get even items
        $evenCount = $quantity - $oddCount;

        return (($price * $evenCount)*$promotion->getAdjustment())+($price * $oddCount);
    }
}