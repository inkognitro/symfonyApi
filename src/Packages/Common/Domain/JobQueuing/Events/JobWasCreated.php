<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing\Events;

use App\Packages\Common\Domain\Event\AuditLogEvent;
use App\Resources\QueueJob\Command;
use App\Resources\AuditLogEvent\EventId;
use App\Resources\AuditLogEvent\OccurredAt;
use App\Resources\AuditLogEvent\Payload;
use App\Resources\AuditLogEvent\ResourceId;
use App\Resources\QueueJob\QueueJob;
use App\Resources\QueueJob\QueueJobId;
use App\Utilities\AuthUser;

final class JobWasCreated extends AuditLogEvent
{
    public static function occur(QueueJobId $jobId, Command $command, AuthUser $creator): self
    {
        $previousPayload = null;
        $occurredAt = OccurredAt::create();
        $resourceId = ResourceId::fromString($jobId->toString());
        $payload = Payload::fromArray([
            Command::getKey() => $command->toSerialized()
        ]);
        return new self(
            EventId::create(), $resourceId, $payload, $creator->toAuditLogEventAuthUserPayload(), $occurredAt
        );
    }

    public static function getResourceType(): string
    {
        return QueueJob::class;
    }

    public function getJobId(): QueueJobId
    {
        return QueueJobId::fromString($this->getResourceId()->toString());
    }

    public function getCommand(): Command
    {
        $commandPayload = $this->getPayload()->toArray()[Command::getKey()];
        return Command::fromSerialized($commandPayload);
    }

    public function mustBeLogged(): bool
    {
        return false;
    }
}