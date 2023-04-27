<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Commands;

use Scrnr\QueryBuilder\Additional\AdditionalMethods;
use Scrnr\QueryBuilder\Conditions\Where;

class Update extends BaseMethods
{
    use AdditionalMethods;

    public function __construct(string $tableName)
    {
        $this->mainTable = $tableName;
        $this->command = "UPDATE {$tableName} SET";
    }

    public function set(array $columns, array $values): static
    {
        if (count($columns) !== count($values)) {
            return $this;
        }

        do {
            $columnName = array_shift($columns);
            $value = array_shift($values);
            $this->addQuotes($value);

            $this->setValuesIntoColumn($columnName, $value);
        } while (!empty($columns));

        return $this;
    }

    public function setPrepare(string ...$columns): static
    {
        do {
            $columnName = array_shift($columns);
            $value = $this->getPrepareValue($columnName, '');

            $this->setValuesIntoColumn($columnName, $value);
        } while (!empty($columns));

        return $this;
    }

    public function where(): Where
    {
        return new Where($this, $this->mainTable);
    }

    protected function getCommandList(): array
    {
        $commandList = ['command', 'values', 'where'];

        return $commandList;
    }

    private function setValuesIntoColumn(string $column, string $value): void
    {
        $this->addSeparator($this->values, ',');
        $this->values .= "{$column} = {$value}";
    }
}
