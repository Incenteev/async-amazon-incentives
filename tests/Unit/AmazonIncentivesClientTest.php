<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\AmazonIncentivesClient;
use Incenteev\AsyncAmazonIncentives\Enum\CurrencyCode;
use Incenteev\AsyncAmazonIncentives\Input\CancelGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\Input\CreateGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\Input\GetAvailableFundsRequest;
use Incenteev\AsyncAmazonIncentives\Result\CancelGiftCardResponse;
use Incenteev\AsyncAmazonIncentives\Result\CreateGiftCardResponse;
use Incenteev\AsyncAmazonIncentives\Result\GetAvailableFundsResponse;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;
use Symfony\Component\HttpClient\MockHttpClient;

class AmazonIncentivesClientTest extends TestCase
{
    public function testCancelGiftCard(): void
    {
        $client = new AmazonIncentivesClient(['region' => 'eu'], new NullProvider(), new MockHttpClient());

        $input = new CancelGiftCardRequest([
            'creationRequestId' => 'change me',
            'partnerId' => 'change me',
            'gcId' => 'change me',
        ]);
        $result = $client->cancelGiftCard($input);

        self::assertInstanceOf(CancelGiftCardResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateGiftCard(): void
    {
        $client = new AmazonIncentivesClient(['region' => 'eu'], new NullProvider(), new MockHttpClient());

        $input = new CreateGiftCardRequest([
            'creationRequestId' => 'change me',
            'partnerId' => 'change me',
            'value' => new MoneyAmount([
                'amount' => 1337,
                'currencyCode' => CurrencyCode::EUR,
            ]),
        ]);
        $result = $client->createGiftCard($input);

        self::assertInstanceOf(CreateGiftCardResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetAvailableFunds(): void
    {
        $client = new AmazonIncentivesClient(['region' => 'eu'], new NullProvider(), new MockHttpClient());

        $input = new GetAvailableFundsRequest([
            'partnerId' => 'change me',
        ]);
        $result = $client->getAvailableFunds($input);

        self::assertInstanceOf(GetAvailableFundsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
