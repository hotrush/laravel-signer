<?php

declare(strict_types=1);

namespace Hotrush\Signer\Tests;

use Hotrush\Signer\Signature;
use Illuminate\Foundation\Testing\WithFaker;

class SignatureTest extends TestCase
{
    use WithFaker;

    public function test_encoding(): void
    {
        $hash = $this->faker->uuid();
        $expiresAt = now()->addHour();
        $payload = ['email' => $this->faker->email()];

        $signature = (string) new Signature($hash, $expiresAt, $payload);

        $this->assertNotEmpty($signature);

        $signatureDecoded = Signature::decode($signature);

        $this->assertInstanceOf(Signature::class, $signatureDecoded);
        $this->assertEquals($hash, $signatureDecoded->hash);
        $this->assertEquals($expiresAt->toAtomString(), $signatureDecoded->expiresAt->toAtomString());
        $this->assertEquals($payload, $signatureDecoded->payload);
    }

    /**
     * @dataProvider decoding_errors_data
     */
    public function test_decoding_errors(string $signature, string $exception, string $exceptionMessage): void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage($exceptionMessage);

        Signature::decode($signature);
    }

    public function decoding_errors_data(): array
    {
        return [
            ['invalid', \InvalidArgumentException::class, 'Can not decode signature'],
            [
                base64_encode(json_encode(['foo' => 'bar'])),
                \InvalidArgumentException::class,
                'Signature is invalid',
            ],
            [
                base64_encode(json_encode(['hash' => 'qwerty'])),
                \InvalidArgumentException::class,
                'Signature is invalid',
            ],
            [
                base64_encode(json_encode(['hash' => 'qwerty', 'expires_at' => null])),
                \InvalidArgumentException::class,
                'Signature is invalid',
            ],
            [
                base64_encode(json_encode(['hash' => 'qwerty', 'expires_at' => '2022-11-23T16:33:52.000000+0000', 'payload' => 'foo'])),
                \InvalidArgumentException::class,
                'Signature is invalid',
            ],
            [
                base64_encode(json_encode(['hash' => 'qwerty', 'expires_at' => '-11-23T16:33:52.000000+0000', 'payload' => []])),
                \InvalidArgumentException::class,
                'Could not parse',
            ],
        ];
    }
}
