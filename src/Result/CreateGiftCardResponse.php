<?php

namespace Incenteev\AsyncAmazonIncentives\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use Incenteev\AsyncAmazonIncentives\Enum\Status;
use Incenteev\AsyncAmazonIncentives\ValueObject\CardInfo;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;

class CreateGiftCardResponse extends Result
{
    /**
     * @var CardInfo
     */
    private $cardInfo;

    /**
     * @var string
     */
    private $creationRequestId;

    /**
     * @var Status::*
     */
    private $status;

    /**
     * @var string|null
     */
    private $gcClaimCode;

    /**
     * @var string|null
     */
    private $gcId;

    /**
     * @var \DateTimeImmutable|null
     */
    private $gcExpirationDate;

    public function getCardInfo(): CardInfo
    {
        $this->initialize();

        return $this->cardInfo;
    }

    public function getCreationRequestId(): string
    {
        $this->initialize();

        return $this->creationRequestId;
    }

    public function getGcClaimCode(): ?string
    {
        $this->initialize();

        return $this->gcClaimCode;
    }

    public function getGcExpirationDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->gcExpirationDate;
    }

    public function getGcId(): ?string
    {
        $this->initialize();

        return $this->gcId;
    }

    /**
     * @return Status::*
     */
    public function getStatus(): string
    {
        $this->initialize();

        return $this->status;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->cardInfo = new CardInfo([
            'value' => new MoneyAmount([
                'amount' => (float) (string) $data->cardInfo->value->amount,
                'currencyCode' => (string) $data->cardInfo->value->currencyCode,
            ]),
            'cardStatus' => (string) $data->cardInfo->cardStatus,
        ]);
        $this->creationRequestId = (string) $data->creationRequestId;
        $this->status = (string) $data->status;
        $this->gcClaimCode = ($v = $data->gcClaimCode) ? (string) $v : null;
        $this->gcId = ($v = $data->gcId) ? (string) $v : null;
        $this->gcExpirationDate = ($v = $data->gcExpirationDate) ? new \DateTimeImmutable((string) $v) : null;
    }
}
