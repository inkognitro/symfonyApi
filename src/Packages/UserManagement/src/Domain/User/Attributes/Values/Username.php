<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Attributes\Values;

final class Username
{
    public const KEY = User::KEY . '.username';
    private $username;

    private function __construct(string $username)
    {
        $this->username = $username;
    }

    public static function fromString(string $username): self
    {
        return new self($username);
    }

    public function toString(): string
    {
        return $this->username;
    }

    public function isEqual(self $username): bool
    {
        return (strcasecmp($username->toString(), $this->toString()) === 0);
    }

    public function isSame(self $username): bool
    {
        return ($username->toString() === $this->toString());
    }
}