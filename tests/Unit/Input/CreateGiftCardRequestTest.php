<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\Enum\CurrencyCode;
use Incenteev\AsyncAmazonIncentives\Input\CreateGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;

class CreateGiftCardRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateGiftCardRequest([
            'creationRequestId' => 'change me',
            'partnerId' => 'change me',
            'value' => new MoneyAmount([
                'amount' => 1337,
                'currencyCode' => CurrencyCode::EUR,
            ]),
            'programId' => 'change me',
            'externalReference' => 'change me',
        ]);

        // see https://developer.amazon.com/docs/incentives-api/incentives-api.html/API_CreateGiftCard.html
        $expected = '
            POST /CreateGiftCard HTTP/1.0
            Content-Type: application/xml

            <CreateGiftCardRequest>
              <creationRequestId>change me</creationRequestId>
              <partnerId>change me</partnerId>
              <value>
                <amount>1337</amount>
                <currencyCode>EUR</currencyCode>
              </value>
              <programId>change me</programId>
              <externalReference>change me</externalReference>
            </CreateGiftCardRequest>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
