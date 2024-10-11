<?php

namespace Incenteev\AsyncAmazonIncentives;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use Incenteev\AsyncAmazonIncentives\Exception\AccountHasProblemsException;
use Incenteev\AsyncAmazonIncentives\Exception\ActiveContractNotFoundException;
use Incenteev\AsyncAmazonIncentives\Exception\AmountBelowMinThresholdException;
use Incenteev\AsyncAmazonIncentives\Exception\BadInputException;
use Incenteev\AsyncAmazonIncentives\Exception\BlockedCustomerException;
use Incenteev\AsyncAmazonIncentives\Exception\CancelRequestArrivedAfterTimeLimitException;
use Incenteev\AsyncAmazonIncentives\Exception\CardNotFoundException;
use Incenteev\AsyncAmazonIncentives\Exception\CurrencyCodeMismatchException;
use Incenteev\AsyncAmazonIncentives\Exception\CustomerAccountBlockedException;
use Incenteev\AsyncAmazonIncentives\Exception\ExternalReferenceTooLongException;
use Incenteev\AsyncAmazonIncentives\Exception\FractionalAmountNotAllowedException;
use Incenteev\AsyncAmazonIncentives\Exception\GcNotReadyForRefundException;
use Incenteev\AsyncAmazonIncentives\Exception\GeneralErrorException;
use Incenteev\AsyncAmazonIncentives\Exception\InsufficientFundsException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidAmountInputException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidAmountValueException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidAwsAccessKeyIdException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidCardNumberInputException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidCurrencyCodeInputException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidCurrencyInMarketplaceException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidGCIdInputException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidPartnerIdInputException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidProgramIdException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidRequestIdInputException;
use Incenteev\AsyncAmazonIncentives\Exception\InvalidRequestInputException;
use Incenteev\AsyncAmazonIncentives\Exception\IssuanceCapExceededException;
use Incenteev\AsyncAmazonIncentives\Exception\MaxAmountExceededException;
use Incenteev\AsyncAmazonIncentives\Exception\NegativeOrZeroAmountException;
use Incenteev\AsyncAmazonIncentives\Exception\OperationNotPermittedException;
use Incenteev\AsyncAmazonIncentives\Exception\ProgramIdNotPresentException;
use Incenteev\AsyncAmazonIncentives\Exception\ProgramIsNotApprovedException;
use Incenteev\AsyncAmazonIncentives\Exception\RequestedDenominationMismatchException;
use Incenteev\AsyncAmazonIncentives\Exception\RequestIdMustStartWithPartnerNameException;
use Incenteev\AsyncAmazonIncentives\Exception\RequestIdTooLongException;
use Incenteev\AsyncAmazonIncentives\Exception\SystemTemporarilyUnavailableException;
use Incenteev\AsyncAmazonIncentives\Exception\ThrottlingException;
use Incenteev\AsyncAmazonIncentives\Exception\UnknownCustomerException;
use Incenteev\AsyncAmazonIncentives\Input\CancelGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\Input\CreateGiftCardRequest;
use Incenteev\AsyncAmazonIncentives\Input\GetAvailableFundsRequest;
use Incenteev\AsyncAmazonIncentives\Result\CancelGiftCardResponse;
use Incenteev\AsyncAmazonIncentives\Result\CreateGiftCardResponse;
use Incenteev\AsyncAmazonIncentives\Result\GetAvailableFundsResponse;
use Incenteev\AsyncAmazonIncentives\ValueObject\MoneyAmount;

class AmazonIncentivesClient extends AbstractApi
{
    /**
     * You can cancel a gift card, as long as the gift card is not claimed by an Amazon customer. The original
     * `creationRequestId` used for creating the gift card will be necessary to cancel a gift card.
     *
     * @see https://developer.amazon.com/docs/incentives-api/incentives-api.html/API_CancelGiftCard.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-incentives-2019-11-29.html#cancelgiftcard
     *
     * @param array{
     *   creationRequestId: string,
     *   partnerId: string,
     *   gcId: string,
     *   '@region'?: string|null,
     * }|CancelGiftCardRequest $input
     *
     * @throws InvalidRequestInputException
     * @throws InvalidCardNumberInputException
     * @throws InvalidPartnerIdInputException
     * @throws InvalidAmountInputException
     * @throws InvalidAmountValueException
     * @throws InvalidCurrencyCodeInputException
     * @throws InvalidRequestIdInputException
     * @throws CardNotFoundException
     * @throws RequestedDenominationMismatchException
     * @throws NegativeOrZeroAmountException
     * @throws MaxAmountExceededException
     * @throws CurrencyCodeMismatchException
     * @throws FractionalAmountNotAllowedException
     * @throws GcNotReadyForRefundException
     * @throws RequestIdTooLongException
     * @throws RequestIdMustStartWithPartnerNameException
     * @throws InvalidGCIdInputException
     * @throws InvalidCurrencyInMarketplaceException
     * @throws AmountBelowMinThresholdException
     * @throws ExternalReferenceTooLongException
     * @throws CancelRequestArrivedAfterTimeLimitException
     * @throws ProgramIdNotPresentException
     * @throws UnknownCustomerException
     * @throws InvalidAwsAccessKeyIdException
     * @throws BlockedCustomerException
     * @throws InsufficientFundsException
     * @throws IssuanceCapExceededException
     * @throws AccountHasProblemsException
     * @throws OperationNotPermittedException
     * @throws BadInputException
     * @throws ActiveContractNotFoundException
     * @throws CustomerAccountBlockedException
     * @throws InvalidProgramIdException
     * @throws ProgramIsNotApprovedException
     * @throws SystemTemporarilyUnavailableException
     * @throws GeneralErrorException
     * @throws ThrottlingException
     */
    public function cancelGiftCard($input): CancelGiftCardResponse
    {
        $input = CancelGiftCardRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CancelGiftCard', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestInput' => InvalidRequestInputException::class,
            'InvalidCardNumberInput' => InvalidCardNumberInputException::class,
            'InvalidPartnerIdInput' => InvalidPartnerIdInputException::class,
            'InvalidAmountInput' => InvalidAmountInputException::class,
            'InvalidAmountValue' => InvalidAmountValueException::class,
            'InvalidCurrencyCodeInput' => InvalidCurrencyCodeInputException::class,
            'InvalidRequestIdInput' => InvalidRequestIdInputException::class,
            'CardNotFound' => CardNotFoundException::class,
            'RequestedDenominationMismatch' => RequestedDenominationMismatchException::class,
            'NegativeOrZeroAmount' => NegativeOrZeroAmountException::class,
            'MaxAmountExceeded' => MaxAmountExceededException::class,
            'CurrencyCodeMismatch' => CurrencyCodeMismatchException::class,
            'FractionalAmountNotAllowed' => FractionalAmountNotAllowedException::class,
            'GcNotReadyForRefund' => GcNotReadyForRefundException::class,
            'RequestIdTooLong' => RequestIdTooLongException::class,
            'RequestIdMustStartWithPartnerName' => RequestIdMustStartWithPartnerNameException::class,
            'InvalidGCIdInput' => InvalidGCIdInputException::class,
            'InvalidCurrencyInMarketplace' => InvalidCurrencyInMarketplaceException::class,
            'AmountBelowMinThreshold' => AmountBelowMinThresholdException::class,
            'ExternalReferenceTooLong' => ExternalReferenceTooLongException::class,
            'CancelRequestArrivedAfterTimeLimit' => CancelRequestArrivedAfterTimeLimitException::class,
            'ProgramIdNotPresent' => ProgramIdNotPresentException::class,
            'UnknownCustomer' => UnknownCustomerException::class,
            'InvalidAwsAccessKeyId' => InvalidAwsAccessKeyIdException::class,
            'BlockedCustomer' => BlockedCustomerException::class,
            'InsufficientFunds' => InsufficientFundsException::class,
            'IssuanceCapExceeded' => IssuanceCapExceededException::class,
            'AccountHasProblems' => AccountHasProblemsException::class,
            'OperationNotPermitted' => OperationNotPermittedException::class,
            'BadInput' => BadInputException::class,
            'ActiveContractNotFound' => ActiveContractNotFoundException::class,
            'CustomerAccountBlocked' => CustomerAccountBlockedException::class,
            'InvalidProgramId' => InvalidProgramIdException::class,
            'ProgramIsNotApproved' => ProgramIsNotApprovedException::class,
            'SystemTemporarilyUnavailable' => SystemTemporarilyUnavailableException::class,
            'GeneralError' => GeneralErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new CancelGiftCardResponse($response);
    }

    /**
     * Creates a live gift card claim code and deducts the amount from the pre-payment account.
     *
     * @see https://developer.amazon.com/docs/incentives-api/incentives-api.html/API_CreateGiftCard.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-incentives-2019-11-29.html#creategiftcard
     *
     * @param array{
     *   creationRequestId: string,
     *   partnerId: string,
     *   value: MoneyAmount|array,
     *   programId?: null|string,
     *   externalReference?: null|string,
     *   '@region'?: string|null,
     * }|CreateGiftCardRequest $input
     *
     * @throws InvalidRequestInputException
     * @throws InvalidCardNumberInputException
     * @throws InvalidPartnerIdInputException
     * @throws InvalidAmountInputException
     * @throws InvalidAmountValueException
     * @throws InvalidCurrencyCodeInputException
     * @throws InvalidRequestIdInputException
     * @throws CardNotFoundException
     * @throws RequestedDenominationMismatchException
     * @throws NegativeOrZeroAmountException
     * @throws MaxAmountExceededException
     * @throws CurrencyCodeMismatchException
     * @throws FractionalAmountNotAllowedException
     * @throws GcNotReadyForRefundException
     * @throws RequestIdTooLongException
     * @throws RequestIdMustStartWithPartnerNameException
     * @throws InvalidGCIdInputException
     * @throws InvalidCurrencyInMarketplaceException
     * @throws AmountBelowMinThresholdException
     * @throws ExternalReferenceTooLongException
     * @throws CancelRequestArrivedAfterTimeLimitException
     * @throws ProgramIdNotPresentException
     * @throws UnknownCustomerException
     * @throws InvalidAwsAccessKeyIdException
     * @throws BlockedCustomerException
     * @throws InsufficientFundsException
     * @throws IssuanceCapExceededException
     * @throws AccountHasProblemsException
     * @throws OperationNotPermittedException
     * @throws BadInputException
     * @throws ActiveContractNotFoundException
     * @throws CustomerAccountBlockedException
     * @throws InvalidProgramIdException
     * @throws ProgramIsNotApprovedException
     * @throws SystemTemporarilyUnavailableException
     * @throws GeneralErrorException
     * @throws ThrottlingException
     */
    public function createGiftCard($input): CreateGiftCardResponse
    {
        $input = CreateGiftCardRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateGiftCard', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestInput' => InvalidRequestInputException::class,
            'InvalidCardNumberInput' => InvalidCardNumberInputException::class,
            'InvalidPartnerIdInput' => InvalidPartnerIdInputException::class,
            'InvalidAmountInput' => InvalidAmountInputException::class,
            'InvalidAmountValue' => InvalidAmountValueException::class,
            'InvalidCurrencyCodeInput' => InvalidCurrencyCodeInputException::class,
            'InvalidRequestIdInput' => InvalidRequestIdInputException::class,
            'CardNotFound' => CardNotFoundException::class,
            'RequestedDenominationMismatch' => RequestedDenominationMismatchException::class,
            'NegativeOrZeroAmount' => NegativeOrZeroAmountException::class,
            'MaxAmountExceeded' => MaxAmountExceededException::class,
            'CurrencyCodeMismatch' => CurrencyCodeMismatchException::class,
            'FractionalAmountNotAllowed' => FractionalAmountNotAllowedException::class,
            'GcNotReadyForRefund' => GcNotReadyForRefundException::class,
            'RequestIdTooLong' => RequestIdTooLongException::class,
            'RequestIdMustStartWithPartnerName' => RequestIdMustStartWithPartnerNameException::class,
            'InvalidGCIdInput' => InvalidGCIdInputException::class,
            'InvalidCurrencyInMarketplace' => InvalidCurrencyInMarketplaceException::class,
            'AmountBelowMinThreshold' => AmountBelowMinThresholdException::class,
            'ExternalReferenceTooLong' => ExternalReferenceTooLongException::class,
            'CancelRequestArrivedAfterTimeLimit' => CancelRequestArrivedAfterTimeLimitException::class,
            'ProgramIdNotPresent' => ProgramIdNotPresentException::class,
            'UnknownCustomer' => UnknownCustomerException::class,
            'InvalidAwsAccessKeyId' => InvalidAwsAccessKeyIdException::class,
            'BlockedCustomer' => BlockedCustomerException::class,
            'InsufficientFunds' => InsufficientFundsException::class,
            'IssuanceCapExceeded' => IssuanceCapExceededException::class,
            'AccountHasProblems' => AccountHasProblemsException::class,
            'OperationNotPermitted' => OperationNotPermittedException::class,
            'BadInput' => BadInputException::class,
            'ActiveContractNotFound' => ActiveContractNotFoundException::class,
            'CustomerAccountBlocked' => CustomerAccountBlockedException::class,
            'InvalidProgramId' => InvalidProgramIdException::class,
            'ProgramIsNotApproved' => ProgramIsNotApprovedException::class,
            'SystemTemporarilyUnavailable' => SystemTemporarilyUnavailableException::class,
            'GeneralError' => GeneralErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new CreateGiftCardResponse($response);
    }

    /**
     * Returns the amount of funds currently available in your Amazon Incentives account.
     *
     * @see https://developer.amazon.com/docs/incentives-api/incentives-api.html/API_GetAvailableFunds.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-incentives-2019-11-29.html#getavailablefunds
     *
     * @param array{
     *   partnerId: string,
     *   '@region'?: string|null,
     * }|GetAvailableFundsRequest $input
     *
     * @throws InvalidRequestInputException
     * @throws InvalidCardNumberInputException
     * @throws InvalidPartnerIdInputException
     * @throws InvalidAmountInputException
     * @throws InvalidAmountValueException
     * @throws InvalidCurrencyCodeInputException
     * @throws InvalidRequestIdInputException
     * @throws CardNotFoundException
     * @throws RequestedDenominationMismatchException
     * @throws NegativeOrZeroAmountException
     * @throws MaxAmountExceededException
     * @throws CurrencyCodeMismatchException
     * @throws FractionalAmountNotAllowedException
     * @throws GcNotReadyForRefundException
     * @throws RequestIdTooLongException
     * @throws RequestIdMustStartWithPartnerNameException
     * @throws InvalidGCIdInputException
     * @throws InvalidCurrencyInMarketplaceException
     * @throws AmountBelowMinThresholdException
     * @throws ExternalReferenceTooLongException
     * @throws CancelRequestArrivedAfterTimeLimitException
     * @throws ProgramIdNotPresentException
     * @throws UnknownCustomerException
     * @throws InvalidAwsAccessKeyIdException
     * @throws BlockedCustomerException
     * @throws InsufficientFundsException
     * @throws IssuanceCapExceededException
     * @throws AccountHasProblemsException
     * @throws OperationNotPermittedException
     * @throws BadInputException
     * @throws ActiveContractNotFoundException
     * @throws CustomerAccountBlockedException
     * @throws InvalidProgramIdException
     * @throws ProgramIsNotApprovedException
     * @throws SystemTemporarilyUnavailableException
     * @throws GeneralErrorException
     * @throws ThrottlingException
     */
    public function getAvailableFunds($input): GetAvailableFundsResponse
    {
        $input = GetAvailableFundsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetAvailableFunds', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestInput' => InvalidRequestInputException::class,
            'InvalidCardNumberInput' => InvalidCardNumberInputException::class,
            'InvalidPartnerIdInput' => InvalidPartnerIdInputException::class,
            'InvalidAmountInput' => InvalidAmountInputException::class,
            'InvalidAmountValue' => InvalidAmountValueException::class,
            'InvalidCurrencyCodeInput' => InvalidCurrencyCodeInputException::class,
            'InvalidRequestIdInput' => InvalidRequestIdInputException::class,
            'CardNotFound' => CardNotFoundException::class,
            'RequestedDenominationMismatch' => RequestedDenominationMismatchException::class,
            'NegativeOrZeroAmount' => NegativeOrZeroAmountException::class,
            'MaxAmountExceeded' => MaxAmountExceededException::class,
            'CurrencyCodeMismatch' => CurrencyCodeMismatchException::class,
            'FractionalAmountNotAllowed' => FractionalAmountNotAllowedException::class,
            'GcNotReadyForRefund' => GcNotReadyForRefundException::class,
            'RequestIdTooLong' => RequestIdTooLongException::class,
            'RequestIdMustStartWithPartnerName' => RequestIdMustStartWithPartnerNameException::class,
            'InvalidGCIdInput' => InvalidGCIdInputException::class,
            'InvalidCurrencyInMarketplace' => InvalidCurrencyInMarketplaceException::class,
            'AmountBelowMinThreshold' => AmountBelowMinThresholdException::class,
            'ExternalReferenceTooLong' => ExternalReferenceTooLongException::class,
            'CancelRequestArrivedAfterTimeLimit' => CancelRequestArrivedAfterTimeLimitException::class,
            'ProgramIdNotPresent' => ProgramIdNotPresentException::class,
            'UnknownCustomer' => UnknownCustomerException::class,
            'InvalidAwsAccessKeyId' => InvalidAwsAccessKeyIdException::class,
            'BlockedCustomer' => BlockedCustomerException::class,
            'InsufficientFunds' => InsufficientFundsException::class,
            'IssuanceCapExceeded' => IssuanceCapExceededException::class,
            'AccountHasProblems' => AccountHasProblemsException::class,
            'OperationNotPermitted' => OperationNotPermittedException::class,
            'BadInput' => BadInputException::class,
            'ActiveContractNotFound' => ActiveContractNotFoundException::class,
            'CustomerAccountBlocked' => CustomerAccountBlockedException::class,
            'InvalidProgramId' => InvalidProgramIdException::class,
            'ProgramIsNotApproved' => ProgramIsNotApprovedException::class,
            'SystemTemporarilyUnavailable' => SystemTemporarilyUnavailableException::class,
            'GeneralError' => GeneralErrorException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new GetAvailableFundsResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new AmazonIncentivesErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'eu':
                return [
                    'endpoint' => 'https://agcod-v2-eu.amazon.com',
                    'signRegion' => 'eu-west-1',
                    'signService' => 'AGCODService',
                    'signVersions' => ['v4'],
                ];
            case 'eu-sandbox':
                return [
                    'endpoint' => 'https://agcod-v2-eu-gamma.amazon.com',
                    'signRegion' => 'eu-west-1',
                    'signService' => 'AGCODService',
                    'signVersions' => ['v4'],
                ];
            case 'fe':
                return [
                    'endpoint' => 'https://agcod-v2-fe.amazon.com',
                    'signRegion' => 'eu-west-1',
                    'signService' => 'AGCODService',
                    'signVersions' => ['v4'],
                ];
            case 'fe-sandbox':
                return [
                    'endpoint' => 'https://agcod-v2-fe-gamma.amazon.com',
                    'signRegion' => 'eu-west-1',
                    'signService' => 'AGCODService',
                    'signVersions' => ['v4'],
                ];
            case 'na':
                return [
                    'endpoint' => 'https://agcod-v2.amazon.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'AGCODService',
                    'signVersions' => ['v4'],
                ];
            case 'na-sandbox':
                return [
                    'endpoint' => 'https://agcod-v2-gamma.amazon.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'AGCODService',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(\sprintf('The region "%s" is not supported by "AmazonIncentives".', $region));
    }
}
