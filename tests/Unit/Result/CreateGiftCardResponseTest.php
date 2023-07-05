<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\Enum\CurrencyCode;
use Incenteev\AsyncAmazonIncentives\Enum\Status;
use Incenteev\AsyncAmazonIncentives\Result\CreateGiftCardResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateGiftCardResponseTest extends TestCase
{
    public function testCreateGiftCardResponse(): void
    {
        // see https://developer.amazon.com/docs/incentives-api/digital-gift-cards.html#creategiftcard
        $response = new SimpleMockedResponse('<CreateGiftCardResponse>
    <gcClaimCode>W3GU-YD4NGH-88C8</gcClaimCode>
    <cardInfo>
        <value>
            <currencyCode>EUR</currencyCode>
            <amount>1.00</amount>
        </value>
        <cardStatus>Fulfilled</cardStatus>
    </cardInfo>
    <gcId>A3B6AC387ESRIX</gcId>
    <creationRequestId>AwssbTSpecTest001</creationRequestId>
    <gcExpirationDate>Mon Jun 09 21:59:59 UTC 2025</gcExpirationDate>
    <status>SUCCESS</status>
</CreateGiftCardResponse>');

        $client = new MockHttpClient($response);
        $result = new CreateGiftCardResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(1.0, $result->getCardInfo()->getValue()->getAmount());
        self::assertSame(CurrencyCode::EUR, $result->getCardInfo()->getValue()->getCurrencyCode());
        self::assertSame('Fulfilled', $result->getCardInfo()->getCardStatus());
        self::assertSame('AwssbTSpecTest001', $result->getCreationRequestId());
        self::assertSame(Status::SUCCESS, $result->getStatus());
        self::assertSame('W3GU-YD4NGH-88C8', $result->getGcClaimCode());
        self::assertSame('A3B6AC387ESRIX', $result->getGcId());
        // self::assertTODO(expected, $result->getGcExpirationDate());
    }
}
