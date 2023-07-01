<?php

namespace Incenteev\AsyncAmazonIncentives;

use AsyncAws\Core\AwsError\AwsError;
use AsyncAws\Core\AwsError\AwsErrorFactoryFromResponseTrait;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\Exception\RuntimeException;
use AsyncAws\Core\Exception\UnexpectedValue;
use AsyncAws\Core\Exception\UnparsableResponse;

class AmazonIncentivesErrorFactory implements AwsErrorFactoryInterface
{
    use AwsErrorFactoryFromResponseTrait;

    public function createFromContent(string $content, array $headers): AwsError
    {
        try {
            set_error_handler(static function ($errno, $errstr) {
                throw new RuntimeException($errstr, $errno);
            });

            try {
                $xml = new \SimpleXMLElement($content);
            } finally {
                restore_error_handler();
            }

            return self::parseXml($xml);
        } catch (\Throwable $e) {
            throw new UnparsableResponse('Failed to parse AWS error: ' . $content, 0, $e);
        }
    }

    private static function parseXml(\SimpleXMLElement $xml): AwsError
    {
        if ('ThrottlingException' === $xml->getName() && 1 === $xml->Message->count()) {
            return new AwsError('ThrottlingException', $xml->Message->__toString(), null, null);
        }

        if (1 === $xml->errorType->count() && 1 === $xml->Message->count()) {
            return new AwsError(
                $xml->errorType->__toString(),
                $xml->Message->__toString(),
                null,
                null
            );
        }

        throw new UnexpectedValue('XML does not contains AWS Error');
    }
}
