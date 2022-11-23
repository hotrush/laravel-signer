<?php

declare(strict_types=1);

namespace Hotrush\Signer\Contracts;

use Carbon\Carbon;
use Hotrush\Signer\Signature;

interface Signable
{
    /**
     * Return null if never expires.
     *
     * @return Carbon|null
     */
    public function getSignExpiration(): ?Carbon;

    /**
     * Payload used for making a sign's hash.
     *
     * @return array
     */
    public function getSignPayload(): array;

    /**
     * Payload put into encoded code and will be publicly accessible.
     *
     * @return array
     */
    public function getPublicSignPayload(): array;

    /**
     * Find signable by sign.
     *
     * @param  Signature  $signature
     * @return static|null
     */
    public static function findSignable(Signature $signature): ?self;

    /**
     * Query builder clauses to be applied to find signable.
     *
     * @param  Signature  $signature
     * @return \Closure
     */
    public static function signableClauses(Signature $signature): \Closure;
}
