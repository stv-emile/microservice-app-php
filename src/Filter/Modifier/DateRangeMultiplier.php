<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class DateRangeMultiplier implements PriceModifierInterface
{

    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {

        $requestDate = $enquiry->getRequestDate();
        $from = $promotion->getCriteria()['from'];
        $to = $promotion->getCriteria()['to'];

        if(!($requestDate >= $from && $requestDate <= $to )) {
            return ($price * $quantity);
        }

        //(price*quantity) * promotion->adjustment
        return ($price * $quantity) * $promotion->getAdjustment();
    }
}