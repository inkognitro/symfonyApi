<?php declare(strict_types=1);

namespace App\Resources\QueueJob;

use App\Resources\Attribute;
use App\Packages\Common\Application\Command as CommandBusCommand;

final class Command implements Attribute
{
    private $command;

    private function __construct(CommandBusCommand $command)
    {
        $this->command = $command;
    }

    public static function getKey(): string
    {
        return 'queueJob.command';
    }

    public static function fromCommand(CommandBusCommand $command): self
    {
        return new self($command);
    }

    public static function fromSerialized(string $serialized): self
    {
        return new self(unserialize($serialized));
    }

    public function toSerialized(): string
    {
        return serialize($this->command);
    }
}