<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Messages;

interface Message
{
    public function getMessage(): string;
}