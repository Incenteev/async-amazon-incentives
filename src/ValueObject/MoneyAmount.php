<?php

namespace Incenteev\AsyncAmazonIncentives\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use Incenteev\AsyncAmazonIncentives\Enum\CurrencyCode;

final class MoneyAmount
{
    /**
     * @var float
     */
    private $amount;

    /**
     * @var CurrencyCode::*
     */
    private $currencyCode;

    /**
     * @param array{
     *   amount: float,
     *   currencyCode: CurrencyCode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->amount = $input['amount'] ?? $this->throwException(new InvalidArgument('Missing required field "amount".'));
        $this->currencyCode = $input['currencyCode'] ?? $this->throwException(new InvalidArgument('Missing required field "currencyCode".'));
    }

    /**
     * @param array{
     *   amount: float,
     *   currencyCode: CurrencyCode::*,
     * }|MoneyAmount $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return CurrencyCode::*
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->amount;
        $node->appendChild($document->createElement('amount', (string) $v));
        $v = $this->currencyCode;
        if (!CurrencyCode::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "currencyCode" for "%s". The value "%s" is not a valid "CurrencyCode".', __CLASS__, $v));
        }
        $node->appendChild($document->createElement('currencyCode', $v));
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
