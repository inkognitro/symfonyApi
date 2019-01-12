<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\Common\Application\Resources\CreatedAt;
use App\Packages\Common\Application\Resources\UpdatedAt;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Application\Resources\User\EmailAddress;
use App\Packages\UserManagement\Application\Resources\User\Password;
use App\Packages\UserManagement\Application\Resources\User\Role;
use App\Packages\UserManagement\Application\Resources\User\Username;
use App\Packages\UserManagement\Application\Resources\User\UserRepository;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\User;
use Doctrine\DBAL\Query\QueryBuilder;

final class DbalUserRepository implements UserRepository
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(UserId $id): ?User
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->andWhere("id = {$queryBuilder->createNamedParameter($id->toString())}");
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        return $this->createUser($row);
    }

    public function findByUsername(Username $username): ?User
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->andWhere("username = {$queryBuilder->createNamedParameter($username->toString())}");
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        return $this->createUser($row);
    }

    public function findByEmailAddress(EmailAddress $emailAddress): ?User
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->andWhere("email_address = {$queryBuilder->createNamedParameter($emailAddress->toString())}");
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        return $this->createUser($row);
    }

    private function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('username');
        $queryBuilder->addSelect('email_address as emailAddress');
        $queryBuilder->addSelect('password');
        $queryBuilder->addSelect('role');
        $queryBuilder->addSelect('created_at as createdAt');
        $queryBuilder->addSelect('updated_at as updatedAt');
        $queryBuilder->from('users');
        return $queryBuilder;
    }

    private function createUser(array $data): User
    {
        $updatedAt = ($data['updatedAt'] === null ? null : UpdatedAt::fromString($data['updatedAt']));
        return new User(
            UserId::fromString($data['id']),
            Username::fromString($data['username']),
            EmailAddress::fromString($data['emailAddress']),
            Password::fromHash($data['password']),
            Role::fromString($data['role']),
            CreatedAt::fromString($data['createdAt']),
            $updatedAt
        );
    }
}