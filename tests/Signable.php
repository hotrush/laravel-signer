<?php

declare(strict_types=1);

namespace Hotrush\Signer\Tests;

use Carbon\Carbon;
use Hotrush\Signer\Contracts\Signable as SignableContract;
use Hotrush\Signer\Signature;
use Hotrush\Signer\Traits\CanBeSigned;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Signable extends Model implements SignableContract
{
    use CanBeSigned;

    protected $fillable = [
        'id', 'name', 'email',
    ];

    public function getSignExpiration(): ?Carbon
    {
        return now()->addHour();
    }

    public function getSignPayload(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }

    public function getPublicSignPayload(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public static function signableClauses(Signature $signature): \Closure
    {
        return function (Builder $query) use ($signature) {
            $query->where('email', $signature->payload['email']);
        };
    }
}
