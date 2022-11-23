<?php

declare(strict_types=1);

namespace Hotrush\Signer\Tests;

use Hotrush\Signer\Facades\Signer;

class TraitTest extends TestCase
{
    public function test_find_by_signature(): void
    {
        $signable = Signable::create(['id' => 1, 'name' => 'Signable #1', 'email' => 'signable@mail.com']);

        $signature = Signer::generate($signable);

        $signableFound = Signable::findSignable($signature);

        $this->assertNotNull($signableFound);
        $this->assertInstanceOf(Signable::class, $signableFound);
        $this->assertEquals($signable->id, $signableFound->id);
    }
}
