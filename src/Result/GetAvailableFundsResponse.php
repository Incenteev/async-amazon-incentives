<?php

namespace Incenteev\AsyncAmazonIncentives\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use Incenteev\AsyncAmazonIncentives\Enum\Status;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;

class GetAvailableFundsResponse extends Result
{
    /**
     * @var MoneyAmount
     */
    private $availableFunds;

    /**
     * @var Status::*
     */
    private $status;

    /**
     * @var \DateTimeImmutable
     */
    private $timestamp;

    public function getAvailableFunds(): MoneyAmount
    {
        $this->initialize();

        return $this->availableFunds;
    }

    /**
     * @return Status::*
     */
    public function getStatus(): string
    {
        $this->initialize();

        return $this->status;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        $this->initialize();

        return $this->timestamp;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->availableFunds = new MoneyAmount([
            'amount' => (float) (string) $data->availableFunds->amount,
            'currencyCode' => (string) $data->availableFunds->currencyCode,
        ]);
        $this->status = (string) $data->status;
        $this->timestamp = new \DateTimeImmutable((string) $data->timestamp);
    }
}
