<?php

namespace Lib\Verifier;

class ChainVerifier implements VerifierInterface
{
    /**
     * @var array
     */
    private $verifiers;

    public function __construct(array $verifiers)
    {
        array_walk($verifiers, function ($verifier) {
            if (!$verifier instanceof VerifierInterface) {
                throw new \InvalidArgumentException('Verifier have to implement Verifier\VerifierInterface');
            }
        });

        $this->verifiers = $verifiers;
    }

    public function verify($value)
    {
        foreach ($this->verifiers as $verifier) {
                $verifier->verify($value);
        }
    }
}
