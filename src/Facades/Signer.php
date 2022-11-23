<?php

declare(strict_types=1);

namespace Hotrush\Signer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hotrush\Signer\Signer
 */
class Signer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Hotrush\Signer\Signer::class;
    }
}
