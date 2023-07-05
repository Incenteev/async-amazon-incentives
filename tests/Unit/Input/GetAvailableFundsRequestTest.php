<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\Input\GetAvailableFundsRequest;

class GetAvailableFundsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetAvailableFundsRequest([
            'partnerId' => 'change me',
        ]);

        // see https://developer.amazon.com/docs/incentives-api/incentives-api.html/API_GetAvailableFunds.html
        $expected = '
            POST /GetAvailableFunds HTTP/1.0
            Content-Type: application/xml

            <GetAvailableFundsRequest>
              <partnerId>change me</partnerId>
            </GetAvailableFundsRequest>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
