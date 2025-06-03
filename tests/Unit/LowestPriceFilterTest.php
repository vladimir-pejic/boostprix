<?php

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\LowestPriceFilter;
use App\Tests\ServiceTestCase;

class LowestPriceFilterTest extends ServiceTestCase
{
    /** @test */
    public function lowest_price_promotion_filter_is_applied_correctly(): void
    {
        // Given
        $enquiry = new LowestPriceEnquiry();
        $promotions = $this->promotionsDataProvider();
        $lowestPriceFilter = $this->container->get(LowestPriceFilter::class);

        // When
        $filteredEnquiry = $lowestPriceFilter->apply($enquiry, ...$promotions);

        // Then
        $this->assertSame(100, $filteredEnquiry->getPrice());
        $this->assertSame(50, $filteredEnquiry->getDiscountedPrice());
        $this->assertSame('Black Friday Bunduru', $filteredEnquiry->getPromotionName());

    }

    public function promotionsDataProvider(): array
    {
        $promotionOne = new Promotion();
        $promotionOne->setName('Black Friday Half-Price Sale!');
        $promotionOne->setType('date_range_multiplier');
        $promotionOne->setAdjustment(0.5);
        $promotionOne->setCriteria(["from" => "2025-06-01", "to" => "2025-06-30"]);

        $promotionTwo = new Promotion();
        $promotionTwo->setName('Voucher OU812');
        $promotionTwo->setType('fixed_price_voucher');
        $promotionTwo->setAdjustment(100);
        $promotionTwo->setCriteria(["code" => "OU812"]);

        return [$promotionOne, $promotionTwo];
    }
}
