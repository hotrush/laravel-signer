<?php

declare(strict_types=1);

namespace Hotrush\Signer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Hotrush\Signer\Signature generate(\Hotrush\Signer\Contracts\Signable $signable)
 * @method static bool validate(\Hotrush\Signer\Contracts\Signable $signable, \Hotrush\Signer\Signature $signature)
 *
 * @see \Hotrush\Signer\Signer
 */
class Signer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Hotrush\Signer\Signer::class;
    }
}
