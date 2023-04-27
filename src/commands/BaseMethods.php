<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Commands;

use Scrnr\QueryBuilder\Additional\AdditionalMethods;

abstract class BaseMethods
{
    use AdditionalMethods;

    public string $having = '';
    public string $where = '';

    public array $preparedColumns = [];

    protected string $additionalTable = '';
    protected string $command = '';
    protected string $mainTable = '';
    protected string $query = '';
    protected string $values = '';

    abstract protected function getCommandList(): array;

    public function getAll(?array $values = null): array
    {
        $this->createQuery();

        if (!empty($values)) {
            $emptyPrepareColumns = array_filter($this->preparedColumns, fn (?string $value): bool => empty($value));

            if (count($values) !== count($emptyPrepareColumns)) {
                return [$this->query, $this->preparedColumns];
            }

            $i = -1;
            foreach ($emptyPrepareColumns as $key => $value) {
                $this->preparedColumns[$key] = $values[++$i];
            }
        }

        return [$this->query, $this->preparedColumns];
    }

    public function getQuery(bool $withValues = true): string
    {
        $this->createQuery();

        if ($withValues) {
            $emptyCountValues = array_reduce($this->preparedColumns, fn (int $carry, mixed $value): int => (empty($value) ? ++$carry : $carry), 0);

            if ($emptyCountValues > 0) {
                return $this->query;
            }

            preg_replace_callback('#(?P<key>.+)#', function (array $matches): void {
                $this->query = preg_replace("#{$matches['key']}#", $this->preparedColumns[$matches['key']], $this->query, 1);
            }, array_keys($this->preparedColumns));
        }

        return $this->query;
    }

    protected function checkTableName(): void
    {
        if (empty($this->additionalTable)) {
            $this->additionalTable = $this->mainTable;
        }
    }

    protected function addBrackets(string &$value): void
    {
        $value = '(' . $value . ')';
    }

    protected function addQuotes(string|int &$value): void
    {
        $value = '"' . $value . '"';
    }

    private function createQuery(): void
    {
        $commands = $this->getReadyCommands();

        foreach ($commands as $command) {
            $this->addSeparator($this->query, ' ');
            $this->query .= $command;
        }
    }

    private function getReadyCommands(): array
    {
        $commandList = $this->getCommandList();
        $commands = [];

        foreach ($commandList as $command) {
            if (!empty($this->$command)) {
                $commands[] = $this->$command;
            }
        }

        return $commands;
    }
}
