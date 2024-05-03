<?php

namespace Incenteev\AsyncAmazonIncentives\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;

final class CreateGiftCardRequest extends Input
{
    /**
     * A unique identifier for every `CreateGiftCard` request. You must generate a new value for every Create request
     * (except for retries).
     *
     * the ID must start with your `PartnerId` and must be at most 40 alphanumeric characters.
     *
     * @required
     *
     * @var string|null
     */
    private $creationRequestId;

    /**
     * A unique identifier (CASE SENSITIVE, 1st letter is capitalized and the next four are lower case) provided by the
     * Amazon team.
     *
     * @required
     *
     * @var string|null
     */
    private $partnerId;

    /**
     * @required
     *
     * @var MoneyAmount|null
     */
    private $value;

    /**
     * @var string|null
     */
    private $programId;

    /**
     * @var string|null
     */
    private $externalReference;

    /**
     * @param array{
     *   creationRequestId?: string,
     *   partnerId?: string,
     *   value?: MoneyAmount|array,
     *   programId?: null|string,
     *   externalReference?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->creationRequestId = $input['creationRequestId'] ?? null;
        $this->partnerId = $input['partnerId'] ?? null;
        $this->value = isset($input['value']) ? MoneyAmount::create($input['value']) : null;
        $this->programId = $input['programId'] ?? null;
        $this->externalReference = $input['externalReference'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   creationRequestId?: string,
     *   partnerId?: string,
     *   value?: MoneyAmount|array,
     *   programId?: null|string,
     *   externalReference?: null|string,
     *   '@region'?: string|null,
     * }|CreateGiftCardRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationRequestId(): ?string
    {
        return $this->creationRequestId;
    }

    public function getExternalReference(): ?string
    {
        return $this->externalReference;
    }

    public function getPartnerId(): ?string
    {
        return $this->partnerId;
    }

    public function getProgramId(): ?string
    {
        return $this->programId;
    }

    public function getValue(): ?MoneyAmount
    {
        return $this->value;
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
        $uriString = '/CreateGiftCard';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $document->appendChild($child = $document->createElement('CreateGiftCardRequest'));

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

    public function setExternalReference(?string $value): self
    {
        $this->externalReference = $value;

        return $this;
    }

    public function setPartnerId(?string $value): self
    {
        $this->partnerId = $value;

        return $this;
    }

    public function setProgramId(?string $value): self
    {
        $this->programId = $value;

        return $this;
    }

    public function setValue(?MoneyAmount $value): self
    {
        $this->value = $value;

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
        if (null === $v = $this->value) {
            throw new InvalidArgument(sprintf('Missing parameter "value" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('value'));

        $v->requestBody($child, $document);

        if (null !== $v = $this->programId) {
            $node->appendChild($document->createElement('programId', $v));
        }
        if (null !== $v = $this->externalReference) {
            $node->appendChild($document->createElement('externalReference', $v));
        }
    }
}
