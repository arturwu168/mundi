<?php
declare(strict_types=1);

namespace Lcobucci\JWT\Signer\Hmac;

use Lcobucci\JWT\Signer\Hmac;

final class Sha384 extends Hmac
{
    public function getAlgorithmId(): string
    {
        return 'HS384';
    }

    public function getAlgorithm(): string
    {
        return 'sha384';
    }
}