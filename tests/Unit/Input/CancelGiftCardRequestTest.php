<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\Input\CancelGiftCardRequest;

class CancelGiftCardRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CancelGiftCardRequest([
            'creationRequestId' => 'change me',
            'partnerId' => 'change me',
            'gcId' => 'change me',
        ]);

        // see https://developer.amazon.com/docs/incentives-api/incentives-api.html/API_CancelGiftCard.html
        $expected = '
            POST /CancelGiftCard HTTP/1.0
            Content-Type: application/xml

            <CancelGiftCardRequest>
              <creationRequestId>change me</creationRequestId>
              <partnerId>change me</partnerId>
              <gcId>change me</gcId>
            </CancelGiftCardRequest>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
