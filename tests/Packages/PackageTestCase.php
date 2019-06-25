<?php declare(strict_types=1);

namespace App\Tests\Packages;

use App\Utilities\Authentication\AuthUser as AuthUser;
use App\Packages\Common\Application\CommandBus;
use App\Tests\TestCase;

abstract class PackageTestCase extends TestCase
{
    /** @var PackageServiceAdapter */
    private $serviceAdapter;

    protected function setUp()
    {
        parent::setUp();
        $this->serviceAdapter = $this->getContainer()->get(PackageServiceAdapter::class);
    }

    protected function createSystemAuthUser(): AuthUser
    {
        return $this->serviceAdapter->getAuthUserFactory()->createSystemUser();
    }

    protected function getCommandBus(): CommandBus
    {
       return $this->serviceAdapter->getCommandBus();
    }
}
