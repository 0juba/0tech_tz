<?php

namespace Verifier;

use Verifier\Exception\DomainNotFoundException;

class DomainExistenceVerifier implements VerifierInterface
{
    public function verify($email)
    {
        list(, $domain) = explode('@', $email) + array(null, null);

        if ($domain && !filter_var(gethostbyname($domain), FILTER_VALIDATE_IP)) {
            throw new DomainNotFoundException(sprintf('Cannot resolve domain <%s> for email <%s>.', $domain, $email));
        }
    }
}
