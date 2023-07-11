<?php

namespace Incenteev\AsyncAmazonIncentives\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Test\TestCase;
use Incenteev\AsyncAmazonIncentives\AmazonIncentivesClient;
use Incenteev\AsyncAmazonIncentives\Enum\CurrencyCode;
use Incenteev\AsyncAmazonIncentives\Enum\Status;
use Incenteev\AsyncAmazonIncentives\Exception\InsufficientFundsException;
use Incenteev\AsyncAmazonIncentives\Exception\ThrottlingException;
use Incenteev\AsyncAmazonIncentives\Input\CancelGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\Input\CreateGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\Input\GetAvailableFundsRequest;
use Incenteev\AsyncAmazonIncentives\Region;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;
use PHPUnit\Framework\Attributes\Group;

#[Group('integration')]
class AmazonIncentivesClientTest extends TestCase
{
    public function testCancelGiftCard(): void
    {
        $client = $this->getClient();

        $input = new CancelGiftCardRequest([
            'creationRequestId' => 'F0000',
            'partnerId' => $this->getPartnerId(),
            'gcId' => 'AMAZONGCID',
        ]);

        try {
            $result = $client->cancelGiftCard($input);

            $result->resolve();
        } catch (ThrottlingException $e) {
            self::markTestSkipped('Could not run the integration tests: ' . $e->getMessage());
        }

        self::assertSame('F0000', $result->getCreationRequestId());
        self::assertSame(Status::SUCCESS, $result->getStatus());
    }

    public function testCreateGiftCard(): void
    {
        $client = $this->getClient();

        $input = new CreateGiftCardRequest([
            'creationRequestId' => 'F0000',
            'partnerId' => $this->getPartnerId(),
            'value' => new MoneyAmount([
                'amount' => 10,
                'currencyCode' => CurrencyCode::EUR,
            ]),
        ]);

        try {
            $result = $client->createGiftCard($input);

            $result->resolve();
        } catch (ThrottlingException $e) {
            self::markTestSkipped('Could not run the integration tests: ' . $e->getMessage());
        }

        self::assertSame(10.0, $result->getCardInfo()->getValue()->getAmount());
        self::assertSame('F0000', $result->getCreationRequestId());
        self::assertSame(Status::SUCCESS, $result->getStatus());
        self::assertNotEmpty($result->getGcClaimCode());
        self::assertNotEmpty($result->getGcId());
    }

    public function testCreateGiftCardFailure(): void
    {
        $client = $this->getClient();

        $input = new CreateGiftCardRequest([
            'creationRequestId' => 'F3003',
            'partnerId' => $this->getPartnerId(),
            'value' => new MoneyAmount([
                'amount' => 10,
                'currencyCode' => CurrencyCode::EUR,
            ]),
        ]);

        $this->expectException(InsufficientFundsException::class);

        try {
            $result = $client->createGiftCard($input);

            $result->resolve();
        } catch (ThrottlingException $e) {
            self::markTestSkipped('Could not run the integration tests: ' . $e->getMessage());
        }
    }

    public function testGetAvailableFunds(): void
    {
        $client = $this->getClient();

        $input = new GetAvailableFundsRequest([
            'partnerId' => $this->getPartnerId(),
        ]);

        try {
            $result = $client->getAvailableFunds($input);

            $result->resolve();
        } catch (ThrottlingException $e) {
            self::markTestSkipped('Could not run the integration tests: ' . $e->getMessage());
        }

        self::assertSame(0.0, $result->getAvailableFunds()->getAmount()); // the balance is always 0 for a sandbox
        self::assertSame(Status::SUCCESS, $result->getStatus());
    }

    private function getClient(): AmazonIncentivesClient
    {
        if (!isset($_SERVER['AMAZON_INCENTIVES_ACCESS_KEY'], $_SERVER['AMAZON_INCENTIVES_SECRET_KEY']) || '' === $_SERVER['AMAZON_INCENTIVES_ACCESS_KEY'] || '' === $_SERVER['AMAZON_INCENTIVES_SECRET_KEY']) {
            self::markTestSkipped('Test credentials are not provided.');
        }

        return new AmazonIncentivesClient([
            'region' => Region::EUROPE_AND_ASIA_SANDBOX,
        ], new Credentials($_SERVER['AMAZON_INCENTIVES_ACCESS_KEY'], $_SERVER['AMAZON_INCENTIVES_SECRET_KEY']));
    }

    private function getPartnerId(): string
    {
        if (!isset($_SERVER['AMAZON_INCENTIVES_PARTNER_ID']) || '' === $_SERVER['AMAZON_INCENTIVES_PARTNER_ID']) {
            self::markTestSkipped('Test credentials are not provided.');
        }

        return $_SERVER['AMAZON_INCENTIVES_PARTNER_ID'];
    }
}
