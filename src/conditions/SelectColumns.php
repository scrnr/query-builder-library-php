<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Conditions;

trait SelectColumns
{
    protected string $columns = '';

    public function column(string $column): static
    {
        $this->createNameColumns([$column]);

        return $this;
    }

    public function columns(string ...$columns): static
    {
        $this->createNameColumns($columns);

        return $this;
    }

    public function all(): static
    {
        $this->createNameColumns(['*']);

        return $this;
    }

    public function alias(string $column, ?string $alias = null): static
    {
        $this->checkTableName();

        if (empty($alias)) {
            $alias = "{$this->additionalTable}_{$column}";
        }

        $this->createAlias([$column => $alias]);

        return $this;
    }

    public function aliases(array $columns): static
    {
        $this->createAlias($columns);

        return $this;
    }

    private function createAlias(array $conditions): void
    {
        $newConditions = [];
        
        foreach ($conditions as $column => $alias) {
            $newConditions[] = "{$column} AS {$alias}";
        }

        $this->createNameColumns($newConditions);
    }

    private function createNameColumns(array $conditions): void
    {
        $this->checkTableName();

        foreach ($conditions as $column) {
            $this->addSeparator($this->columns, ',');

            $this->columns .= "{$this->additionalTable}.{$column}";
        }
    }
}
