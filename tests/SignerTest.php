<?php

declare(strict_types=1);

namespace Hotrush\Signer\Tests;

use Carbon\Carbon;
use Hotrush\Signer\Facades\Signer;
use Hotrush\Signer\Signature;

class SignerTest extends TestCase
{
    public function test_signer(): void
    {
        Carbon::setTestNow('2022-11-22');

        $signable = new Signable(['id' => 1, 'name' => 'Signable #1', 'email' => 'signable@mail.com']);
        $signature = Signer::generate($signable);

        $this->assertInstanceOf(Signature::class, $signature);
        $this->assertNotEmpty($signature->hash);
        $this->assertEquals($signable->getPublicSignPayload(), $signature->payload);
        $this->assertEquals($signable->getSignExpiration(), $signature->expiresAt);
        $this->assertTrue(Signer::validate($signable, $signature));

        Carbon::setTestNow();
    }

    public function test_expired_signature(): void
    {
        $signable = new Signable(['id' => 1, 'name' => 'Signable #1', 'email' => 'signable@mail.com']);
        $signature = Signer::generate($signable);

        Carbon::setTestNow(now()->addDay());

        $this->assertFalse(Signer::validate($signable, $signature));

        Carbon::setTestNow();
    }
}
