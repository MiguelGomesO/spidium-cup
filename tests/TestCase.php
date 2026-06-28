<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $database = (string) config('database.connections.' . config('database.default') . '.database');

        if (! str_contains($database, 'test')) {
            throw new RuntimeException(
                "Testes bloqueados: o banco \"{$database}\" não é dedicado a testes. "
                . 'Use spidium_cup_test (phpunit.xml) para não apagar dados de desenvolvimento.',
            );
        }
    }
}
