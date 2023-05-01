<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Conditions;

use Scrnr\QueryBuilder\Additional\AdditionalMethods;
use Scrnr\QueryBuilder\Commands\Select;

class Join
{
    use AdditionalMethods;

    public const INNER = 'INNER JOIN';
    public const LEFT = 'LEFT JOIN';
    public const RIGHT = 'RIGHT JOIN';

    private Select $refererClass;
    private string $joinTable;
    private string $mainTable;
    private string $join = '';

    public function __construct(Select $refererClass, string $mainTable, string $joinTable, string $type)
    {
        $this->refererClass = $refererClass;
        $this->mainTable = $mainTable;
        $this->join($joinTable, $type);
    }

    public function end(): Select
    {
        $this->refererClass->join = $this->join;

        return $this->refererClass;
    }

    public function join(string $joinTable, string $type = Join::INNER): static
    {
        $this->joinTable = $joinTable;

        $this->addSeparator($this->join, ' ');
        $this->join .= "{$type} {$this->joinTable}";


        return $this;
    }

    public function on(string|int $conditionOne, string|int $conditionTwo, ?string $tableOne = null, bool $needSecondTable = true): static
    {
        $this->addCondition($conditionOne, $conditionTwo, $tableOne, $needSecondTable, 'ON');

        return $this;
    }

    public function and(string|int $conditionOne, string|int $conditionTwo, ?string $tableOne = null, bool $needSecondTable = true): static
    {
        $this->addCondition($conditionOne, $conditionTwo, $tableOne, $needSecondTable, 'AND');

        return $this;
    }

    public function or(string|int $conditionOne, string|int $conditionTwo, ?string $tableOne = null, bool $needSecondTable = true): static
    {
        $this->addCondition($conditionOne, $conditionTwo, $tableOne, $needSecondTable, 'OR');

        return $this;
    }

    private function addCondition(string|int $conditionOne, string|int $conditionTwo, ?string $tableOne, bool $needSecondTable, string $separator): void
    {
        $tableOne = empty($tableOne) ? $this->mainTable : $tableOne;
        $condition = "{$tableOne}.{$conditionOne}";

        if ($needSecondTable) {
            $value = "{$this->joinTable}.{$conditionTwo}";
        } else {
            if (is_numeric($conditionTwo)) {
                $value = (string) $conditionTwo;
            } else {
                $value = "'{$conditionTwo}'";
            }
        }

        $this->join .= " {$separator} {$condition} = {$value}";
    }
}
