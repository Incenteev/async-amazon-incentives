<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\Enum\Status;
use Incenteev\AsyncAmazonIncentives\Result\CancelGiftCardResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CancelGiftCardResponseTest extends TestCase
{
    public function testCancelGiftCardResponse(): void
    {
        // see https://developer.amazon.com/docs/incentives-api/digital-gift-cards.html#cancelgiftcard
        $response = new SimpleMockedResponse('<CancelGiftCardResponse>
	<creationRequestId>AwssbTSpecTest001</creationRequestId>
	<status>SUCCESS</status>
</CancelGiftCardResponse>');

        $client = new MockHttpClient($response);
        $result = new CancelGiftCardResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('AwssbTSpecTest001', $result->getCreationRequestId());
        self::assertSame(Status::SUCCESS, $result->getStatus());
    }
}
