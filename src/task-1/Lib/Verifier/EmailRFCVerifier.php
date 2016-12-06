<?php

namespace Lib\Verifier;

use Lib\Verifier\Exception\InvalidEmailException;

class EmailRFCVerifier implements VerifierInterface
{
    public function verify($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException(sprintf('Email address <%s> does not match RFC822 requirements.'));
        }
    }
}
