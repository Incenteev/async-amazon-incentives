<?php

namespace Incenteev\AsyncAmazonIncentives\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetAvailableFundsRequest extends Input
{
    /**
     * @required
     *
     * @var string|null
     */
    private $partnerId;

    /**
     * @param array{
     *   partnerId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->partnerId = $input['partnerId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   partnerId?: string,
     *   '@region'?: string|null,
     * }|GetAvailableFundsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
        $uriString = '/GetAvailableFunds';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $document->appendChild($child = $document->createElement('GetAvailableFundsRequest'));

        $this->requestBody($child, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setPartnerId(?string $value): self
    {
        $this->partnerId = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null === $v = $this->partnerId) {
            throw new InvalidArgument(\sprintf('Missing parameter "partnerId" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('partnerId', $v));
    }
}
