<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Commands;

use Scrnr\QueryBuilder\Additional\AdditionalMethods;

class Insert extends BaseMethods
{
    use AdditionalMethods;

    protected string $columns = '';
    protected string $duplicate = '';
    protected string $set = 'VALUES';

    public function __construct(string $tableName)
    {
        $this->command = "INSERT INTO {$tableName}";
        $this->mainTable = $tableName;
    }

    public function columns(string ...$columns): static
    {
        foreach ($columns as &$column) {
            $column = "{$this->mainTable}.{$column}";
        }

        $column = implode(', ', $columns);
        $this->addBrackets($column);
        $this->columns = $column;

        return $this;
    }

    public function values(array $values): static
    {
        $columnsList = $this->getColumnsList();

        for ($i = 0, $count = count($values); $i < $count;  $i++) {
            $value = &$values[$i];
            $this->addQuotes($value);
            $value = $this->getPrepareValue($columnsList[$i], $value);
        }

        $value = implode(',', $values);
        $this->addBrackets($value);
        $this->addSeparator($this->values, ',');
        $this->values .= $value;

        return $this;
    }

    public function prepareValues(int $times = 1): static
    {
        $columnsList = $this->getColumnsList();

        for ($i = 1; $i <= $times; $i++) {
            $prepareValues = [];

            foreach ($columnsList as $column) {
                $prepareValues[] = $this->getPrepareValue($column, '');
            }

            $values = implode(', ', $prepareValues);
            $this->addBrackets($values);
            $this->addSeparator($this->values, ',');
            $this->values .= $values;
        }

        return $this;
    }

    public function dublicateKey(string|array $columns, string|array $values): static
    {
        $duplicateText = 'ON DUPLICATE KEY UPDATE ';

        if (is_string($columns) and !is_string($values)) {
            return $this;
        } elseif (is_array($columns) and !is_array($values)) {
            return $this;
        }

        do {
            if (is_array($columns)) {
                $column = array_shift($columns);
                $value = array_shift($values);
            } else {
                $column = $columns;
                $value = $values;
                $columns = [];
            }

            $column = "{$this->mainTable}.{$column}";
            $this->addSeparator($this->duplicate, ',');
            $this->addQuotes($value);
            $row = "{$column} = {$value}";
            $this->duplicate .= $row;
        } while (!empty($columns));

        $this->duplicate = $duplicateText . $this->duplicate;

        return $this;
    }

    protected function getCommandList(): array
    {
        $commandList = ['command', 'columns', 'set', 'values', 'duplicate'];

        return $commandList;
    }

    private function getColumnsList(): array
    {
        $columns = trim($this->columns, '()');

        return explode(', ', $columns);
    }
}
