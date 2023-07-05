<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\AmazonIncentivesClient;
use Incenteev\AsyncAmazonIncentives\Enum\CurrencyCode;
use Incenteev\AsyncAmazonIncentives\Enum\Status;
use Incenteev\AsyncAmazonIncentives\Input\CancelGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\Input\CreateGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\Input\GetAvailableFundsRequest;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;

class AmazonIncentivesClientTest extends TestCase
{
    public function testCancelGiftCard(): void
    {
        $client = $this->getClient();

        $input = new CancelGiftCardRequest([
            'creationRequestId' => 'change me',
            'partnerId' => 'change me',
            'gcId' => 'change me',
        ]);
        $result = $client->cancelGiftCard($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getCreationRequestId());
        self::assertSame('changeIt', $result->getStatus());
    }

    public function testCreateGiftCard(): void
    {
        $client = $this->getClient();

        $input = new CreateGiftCardRequest([
            'creationRequestId' => 'change me',
            'partnerId' => 'change me',
            'value' => new MoneyAmount([
                'amount' => 10,
                'currencyCode' => CurrencyCode::EUR,
            ]),
        ]);
        $result = $client->createGiftCard($input);

        $result->resolve();

        self::assertSame(10.0, $result->getCardInfo()->getValue()->getAmount());
        self::assertSame('changeIt', $result->getCreationRequestId());
        self::assertSame(Status::SUCCESS, $result->getStatus());
    }

    public function testGetAvailableFunds(): void
    {
        $client = $this->getClient();

        $input = new GetAvailableFundsRequest([
            'partnerId' => 'change me',
        ]);
        $result = $client->getAvailableFunds($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getAvailableFunds());
        self::assertSame('changeIt', $result->getStatus());
        // self::assertTODO(expected, $result->getTimestamp());
    }

    private function getClient(): AmazonIncentivesClient
    {
        self::markTestIncomplete('Not implemented');

        // @phpstan-ignore-next-line
        return new AmazonIncentivesClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
