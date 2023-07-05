<?php

namespace Incenteev\AsyncAmazonIncentives\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use Incenteev\AsyncAmazonIncentives\Enum\Status;

class CancelGiftCardResponse extends Result
{
    /**
     * @var string
     */
    private $creationRequestId;

    /**
     * @var Status::*
     */
    private $status;

    public function getCreationRequestId(): string
    {
        $this->initialize();

        return $this->creationRequestId;
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
        $this->creationRequestId = (string) $data->creationRequestId;
        $this->status = (string) $data->status;
    }
}
