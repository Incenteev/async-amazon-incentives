Async Amazon Incentives
=======================

This package provides an unofficial SDK for the [Amazon Incentives API](https://developer.amazon.com/docs/incentives-api/digital-gift-cards.html).

## Installation

Use [Composer](https://getcomposer.org) to install the library:

```bash
$ composer require incenteev/async-amazon-incentives
```

## Usage

```php
use AsyncAws\Core\Configuration;
use Incenteev\AsyncAmazonIncentives\AmazonIncentivesClient;
use Incenteev\AsyncAmazonIncentives\Enum\CurrencyCode;
use Incenteev\AsyncAmazonIncentives\Exception\SystemTemporarilyUnavailableException;
use Incenteev\AsyncAmazonIncentives\Region;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;

// Get your credentials in the Amazon Incentives portal
$accessKey = '';
$secretKey = '';
$partnerId = '';

// Choose the region corresponding to your partnership, with either the sandbox or production one.
$region = Region::EUROPE_AND_ASIA_SANDBOX;

$client = new AmazonIncentivesClient([
    Configuration::OPTION_ACCESS_KEY_ID => $accessKey,
    Configuration::OPTION_SECRET_ACCESS_KEY => $secretKey,
    Configuration::OPTION_REGION => $region,
]);

try {
    $result = $client->createGiftCard([
        'partnerId' => $partnerId,
        'creationRequestId' => '', // Create the proper request id
        'value' => new MoneyAmount(['amount' => 10, 'currencyCode' => CurrencyCode::EUR]),
    ]);

    $code = $result->getGcClaimCode();
} catch (SystemTemporarilyUnavailableException $e) {
    // TODO handle temporary failures according to the Amazon Incentives best practices
}
```

> Note: due to the async nature of the project, the exception is not actually thrown by the call to `createGiftCard`
> but when the Result object gets resolved.

## License

This package is under the [MIT license](LICENSE).

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [GitHub issue tracker](https://github.com/Incenteev/async-amazon-incentives/issues).
