<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Fixtures;

abstract class Fixture
{
    public function getSequenceNumber(): int
    {
        return 1;
    }

    public abstract function execute(): void;
}