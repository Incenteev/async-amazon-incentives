<?php

namespace Incenteev\AsyncAmazonIncentives\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

final class ThrottlingException extends ClientException
{
}
