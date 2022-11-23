<?php

declare(strict_types=1);

namespace Hotrush\Signer\Traits;

use Carbon\Carbon;
use Hotrush\Signer\Signature;
use Illuminate\Database\Eloquent\Builder;

trait CanBeSigned
{
    /**
     * Return null if never expires.
     *
     * @return Carbon|null
     */
    public function getSignExpiration(): ?Carbon
    {
        return null;
    }

    /**
     * Payload used for making a sign's hash.
     *
     * @return array
     */
    public function getSignPayload(): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
        ];
    }

    /**
     * Payload put into encoded code and will be publicly accessible.
     *
     * @return array
     */
    public function getPublicSignPayload(): array
    {
        return [
            $this->getKeyName() => $this->getKey(),
        ];
    }

    /**
     * Find signable by signature.
     *
     * @param  Signature  $signature
     * @return static|null
     */
    public static function findSignable(Signature $signature): ?self
    {
        return self::where(self::signableClauses($signature))->first();
    }

    public static function signableClauses(Signature $signature): \Closure
    {
        return function (Builder $query) use ($signature) {
            $query->where('id', '=', $signature->payload['id']);
        };
    }
}
