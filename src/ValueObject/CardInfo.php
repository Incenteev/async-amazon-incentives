<?php

namespace Incenteev\AsyncAmazonIncentives\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class CardInfo
{
    /**
     * @var MoneyAmount
     */
    private $value;

    /**
     * @var string
     */
    private $cardStatus;

    /**
     * @param array{
     *   value: MoneyAmount|array,
     *   cardStatus: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->value = isset($input['value']) ? MoneyAmount::create($input['value']) : $this->throwException(new InvalidArgument('Missing required field "value".'));
        $this->cardStatus = isset($input['cardStatus']) ? $input['cardStatus'] : $this->throwException(new InvalidArgument('Missing required field "cardStatus".'));
    }

    /**
     * @param array{
     *   value: MoneyAmount|array,
     *   cardStatus: string,
     * }|CardInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCardStatus(): string
    {
        return $this->cardStatus;
    }

    public function getValue(): MoneyAmount
    {
        return $this->value;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
