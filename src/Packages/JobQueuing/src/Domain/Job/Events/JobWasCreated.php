<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Domain\Event\AbstractEvent;
use App\Packages\Common\Domain\Event\EventId;
use App\Packages\Common\Domain\Event\OccurredAt;
use App\Packages\JobQueuing\Domain\Job\Events\JobPayload;
use App\Packages\JobQueuing\Domain\Job\Job;

final class JobWasCreated extends AbstractEvent
{
    public static function occur(Job $job, AuthUser $authUser): self
    {
        $previousPayload = null;
        $occurredAt = OccurredAt::create();
        $payload = JobPayload::fromJob($job, [
            'createdAt' => $occurredAt->toString()
        ]);
        return new self(EventId::create(), $occurredAt, $authUser, $payload, $previousPayload);
    }

    public function getJob(): Job
    {
        /** @var $payload JobPayload */
        $payload = $this->getPayload();
        return $payload->toJob();
    }

    public function mustBeLogged(): bool
    {
        return false;
    }
}