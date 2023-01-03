<?php

declare(strict_types=1);

namespace Hotrush\Signer;

use Carbon\Carbon;

final class Signature implements \Stringable
{
    public function __construct(
        public readonly string $hash,
        public readonly ?Carbon $expiresAt,
        public readonly array $payload,
    ) {
    }

    public function __toString(): string
    {
        return $this->encode();
    }

    private function encode(): string
    {
        return base64_encode(json_encode([
            'hash' => $this->hash,
            'expires_at' => $this->expiresAt?->toAtomString(),
            'payload' => $this->payload,
        ]));
    }

    public static function decode(string $signature): self
    {
        $decoded = json_decode((string) base64_decode($signature), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Can not decode signature');
        }

        if (
            ! isset($decoded['hash'])
            || ! array_key_exists('expires_at', $decoded)
            || ! isset($decoded['payload'])
            || ! is_array($decoded['payload'])
        ) {
            throw new \InvalidArgumentException('Signature is invalid');
        }

        return new self(
            $decoded['hash'],
            $decoded['expires_at'] ? Carbon::parse($decoded['expires_at']) : null,
            $decoded['payload']
        );
    }
}
