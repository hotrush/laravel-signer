<?php

declare(strict_types=1);

namespace Hotrush\Signer\Contracts;

use Hotrush\Signer\Signature;

interface Signer
{
    public function generate(Signable $signable): Signature;

    public function validate(Signable $signable, Signature $signature): bool;
}
