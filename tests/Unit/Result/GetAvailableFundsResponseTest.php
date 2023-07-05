<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\Enum\CurrencyCode;
use Incenteev\AsyncAmazonIncentives\Enum\Status;
use Incenteev\AsyncAmazonIncentives\Result\GetAvailableFundsResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetAvailableFundsResponseTest extends TestCase
{
    public function testGetAvailableFundsResponse(): void
    {
        // see https://developer.amazon.com/docs/incentives-api/balance-view.html#responses
        $response = new SimpleMockedResponse('<GetAvailableFunds>
	<availableFunds>
		<amount>10</amount>
		<currencyCode>USD</currencyCode>
	</availableFunds>
	<status>SUCCESS</status>
	<timestamp>20170915T200959Z</timestamp>
</GetAvailableFunds>');

        $client = new MockHttpClient($response);
        $result = new GetAvailableFundsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(10.0, $result->getAvailableFunds()->getAmount());
        self::assertSame(CurrencyCode::USD, $result->getAvailableFunds()->getCurrencyCode());
        self::assertSame(Status::SUCCESS, $result->getStatus());
        self::assertSame('2017-09-15', $result->getTimestamp()->format('Y-m-d'));
    }
}
