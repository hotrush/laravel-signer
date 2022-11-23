<?php

declare(strict_types=1);

namespace Hotrush\Signer;

use Carbon\Carbon;
use Hotrush\Signer\Contracts\Signable;
use Hotrush\Signer\Contracts\Signer as SignerContract;
use Illuminate\Support\Facades\Hash;

class Signer implements SignerContract
{
    public function generate(Signable $signable): Signature
    {
        $expiresAt = $signable->getSignExpiration();
        $payload = $signable->getSignPayload();
        $hash = $this->makeHash($payload, $expiresAt);

        return new Signature($hash, $expiresAt, $signable->getPublicSignPayload());
    }

    public function validate(Signable $signable, Signature $signature): bool
    {
        if ($signature->expiresAt && $signature->expiresAt->isPast()) {
            return false;
        }

        return $this->checkHash($signable->getSignPayload(), $signature);
    }

    private function makeHash(array $payload, ?Carbon $expiresAt): string
    {
        return Hash::make($this->makeStringForHashing($payload, $expiresAt));
    }

    private function makeStringForHashing(array $payload, ?Carbon $expiresAt): string
    {
        $payload['expires_at'] = $expiresAt?->toAtomString();

        return implode('|', $payload);
    }

    private function checkHash(array $payload, Signature $signature): bool
    {
        return Hash::check($this->makeStringForHashing($payload, $signature->expiresAt), $signature->hash);
    }
}
