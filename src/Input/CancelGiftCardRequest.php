<?php

namespace Incenteev\AsyncAmazonIncentives\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CancelGiftCardRequest extends Input
{
    /**
     * @required
     *
     * @var string|null
     */
    private $creationRequestId;

    /**
     * @required
     *
     * @var string|null
     */
    private $partnerId;

    /**
     * @required
     *
     * @var string|null
     */
    private $gcId;

    /**
     * @param array{
     *   creationRequestId?: string,
     *   partnerId?: string,
     *   gcId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->creationRequestId = $input['creationRequestId'] ?? null;
        $this->partnerId = $input['partnerId'] ?? null;
        $this->gcId = $input['gcId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   creationRequestId?: string,
     *   partnerId?: string,
     *   gcId?: string,
     *   '@region'?: string|null,
     * }|CancelGiftCardRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationRequestId(): ?string
    {
        return $this->creationRequestId;
    }

    public function getGcId(): ?string
    {
        return $this->gcId;
    }

    public function getPartnerId(): ?string
    {
        return $this->partnerId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/CancelGiftCard';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $document->appendChild($child = $document->createElement('CancelGiftCardRequest'));

        $this->requestBody($child, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCreationRequestId(?string $value): self
    {
        $this->creationRequestId = $value;

        return $this;
    }

    public function setGcId(?string $value): self
    {
        $this->gcId = $value;

        return $this;
    }

    public function setPartnerId(?string $value): self
    {
        $this->partnerId = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null === $v = $this->creationRequestId) {
            throw new InvalidArgument(sprintf('Missing parameter "creationRequestId" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('creationRequestId', $v));
        if (null === $v = $this->partnerId) {
            throw new InvalidArgument(sprintf('Missing parameter "partnerId" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('partnerId', $v));
        if (null === $v = $this->gcId) {
            throw new InvalidArgument(sprintf('Missing parameter "gcId" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('gcId', $v));
    }
}
