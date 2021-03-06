<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

final class Migrations
{
    private $migrations;

    /** @param $migrations Migration[] */
    public function __construct(array $migrations)
    {
        $this->migrations = $migrations;
    }

    public function merge(self $that): self
    {
        return new self(array_merge($this->toArray(), $that->toArray()));
    }

    /** @return Migration[] */
    public function toArray(): array
    {
        return $this->migrations;
    }

    public function isEmpty(): bool
    {
        return (count($this->migrations) === 0);
    }

    /** @return Migration[] */
    public function toSortedArray(): array
    {
        $sortedMigrations = $this->migrations;
        usort($sortedMigrations, function (Migration $a, Migration $b): int
        {
            if($a->getBatchNumber() < $b->getBatchNumber()) {
                return -1;
            }

            if($a->getBatchNumber() > $b->getBatchNumber()) {
                return 1;
            }

            if($a->getBatchSequenceNumber() < $b->getBatchSequenceNumber()) {
                return -1;
            }

            if($a->getBatchSequenceNumber() > $b->getBatchSequenceNumber()) {
                return 1;
            }

            return 0;
        });
        return $sortedMigrations;
    }

    /** @return Migration[] */
    public function toReverseSortedArray(): array
    {
        return array_reverse($this->toSortedArray());
    }

    public function findAllWithHigherBatchNumber(int $batchNumber): self
    {
        $migrations = [];
        foreach($this->migrations as $migration) {
            if($migration->getBatchNumber() > $batchNumber) {
                $migrations[] = $migration;
            }
        }
        return new self($migrations);
    }

    public function getHighestBatchNumber(): int
    {
        $batchNumber = (count($this->migrations) === 0 ? 0 : $this->migrations[0]->getBatchNumber());
        foreach($this->migrations as $migration) {
            if($migration->getBatchNumber() > $batchNumber) {
                $batchNumber = $migration->getBatchNumber();
            }
        }
        return $batchNumber;
    }

    public function getLowestBatchNumber(): int
    {
        $batchNumber = (count($this->migrations) === 0 ? 0 : $this->migrations[0]->getBatchNumber());
        foreach($this->migrations as $migration) {
            if($migration->getBatchNumber() < $batchNumber) {
                $batchNumber = $migration->getBatchNumber();
            }
        }
        return $batchNumber;
    }

    public function has(Migration $migrationToFind): bool
    {
        foreach($this->migrations as $migration) {
            if(get_class($migration) === get_class($migrationToFind)) {
                return true;
            }
        }
        return false;
    }
}