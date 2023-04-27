<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Conditions;

use Scrnr\QueryBuilder\Additional\AdditionalMethods;
use Scrnr\QueryBuilder\Commands\BaseMethods;
use Scrnr\QueryBuilder\Commands\Delete;
use Scrnr\QueryBuilder\Commands\Select;
use Scrnr\QueryBuilder\Commands\Update;
use Scrnr\QueryBuilder\Sql\SqlFunctions;

abstract class WhereCondition
{
    use AdditionalMethods;

    protected string $query = '';
    protected array $preparedColumns = [];
    protected BaseMethods $refererClass;

    private string $table = '';

    public function __construct(BaseMethods $class, string $tableName)
    {
        $this->refererClass = $class;
        $this->table = $tableName;
    }

    public function end(): Delete | Select | Update
    {
        $preparedColumns = array_merge($this->refererClass->preparedColumns, $this->preparedColumns);
        $this->refererClass->preparedColumns = $preparedColumns;

        return $this->refererClass;
    }

    public function and(): static
    {
        $this->addSeparator($this->query, 'and');

        return $this;
    }

    public function or(): static
    {
        $this->addSeparator($this->query, 'or');

        return $this;
    }

    public function equal(string $column, mixed $value = null, ?string $table = null, ?string $sqlFunction = null): static
    {
        $this->createQuery($column, $table, '=', $value, $sqlFunction);

        return $this;
    }

    public function notEqual(string $column, mixed $value = null, ?string $table = null, ?string $sqlFunction = null): static
    {
        $this->createQuery($column, $table, '<>', $value, $sqlFunction);

        return $this;
    }

    public function less(string $column, mixed $value = null, ?string $table = null, ?string $sqlFunction = null): static
    {
        $this->createQuery($column, $table, '<', $value, $sqlFunction);

        return $this;
    }

    public function lessOrEqual(string $column, mixed $value = null, ?string $table = null, ?string $sqlFunction = null): static
    {
        $this->createQuery($column, $table, '<=', $value, $sqlFunction);

        return $this;
    }

    public function greater(string $column, mixed $value = null, ?string $table = null, ?string $sqlFunction = null): static
    {
        $this->createQuery($column, $table, '>', $value, $sqlFunction);

        return $this;
    }

    public function greaterOrEqual(string $column, mixed $value = null, ?string $table = null, ?string $sqlFunction = null): static
    {
        $this->createQuery($column, $table, '>=', $value, $sqlFunction);

        return $this;
    }

    public function like(string $column, ?string $pattern = null, ?string $table = null): static
    {
        $this->createQuery($column, $table, 'LIKE', $pattern, null);

        return $this;
    }

    public function notLike(string $column, ?string $pattern = null, ?string $table = null): static
    {
        $this->createQuery($column, $table, 'NOT LIKE', $pattern, null);

        return $this;
    }

    public function in(string $column, array|string|null $values = null, ?string $table = null): static
    {
        $this->createIn($column, $values, $table, false);

        return $this;
    }

    public function notIn(string $column, array|string|null $values = null, ?string $table = null): static
    {
        $this->createIn($column, $values, $table, true);

        return $this;
    }

    public function null(string $column, ?string $table = null): static
    {
        $this->createNull($column, $table, false);

        return $this;
    }

    public function notNull(string $column, ?string $table = null): static
    {
        $this->createNull($column, $table, true);

        return $this;
    }

    public function between(string $column, string|int|null $valueOne = null, string|int|null $valueTwo = null, ?string $table = null): static
    {
        $this->createBetween($column, $valueOne, $valueTwo, $table, false);

        return $this;
    }

    public function notBetween(string $column, string|int|null $valueOne = null, string|int|null $valueTwo = null, ?string $table = null): static
    {
        $this->createBetween($column, $valueOne, $valueTwo, $table, true);

        return $this;
    }

    private function createQuery(string $column, ?string $table, string $operator, mixed $value, ?string $sqlFunction): void
    {
        if (is_null($sqlFunction)) {
            $this->checkTableAndColumn($table, $column);
        } else {
            $this->checkTable($table);
            $sql = $this->getSqlFunciton($sqlFunction, $column, $table, null);

            if ($sql !== false) {
                $this->query .= $sql;
                $column = $sqlFunction;
            }
        }

        $value = $this->getValue($value, $column);

        $query = [
            ($sqlFunction === $column ? '' : $column),
            $operator,
            $value
        ];

        $this->query .= implode(' ', $query);
    }

    private function createIn(string $column, array|string|null $values = null, ?string $table = null, bool $not = false): void
    {
        $this->checkTableAndColumn($table, $column);

        if (is_string($values)) {
            $value = $values;
        } elseif (is_array($values)) {
            foreach ($values as &$val) {
                $val = $this->getValue($val, $column);
            }

            $value = implode(', ', $values);
        } elseif (is_null($values)) {
            $value = $values;
        }

        $this->query .= $column . ($not ? ' NOT ' : ' ') . "IN ({$value})";
    }

    private function createNull(string $column, ?string $table, bool $not): void
    {
        $this->checkTableAndColumn($table, $column);
        $this->query .= "{$column} IS" . ($not ? ' NOT ' : ' ') . 'NULL';
    }

    private function createBetween(string $column, string|int|null $valueOne, string|int|null $valueTwo, ?string $table, bool $not): void
    {
        $this->checkTableAndColumn($table, $column);

        $valueOne = $this->getValue($valueOne, $column);
        $valueTwo = $this->getValue($valueTwo, $column);

        $this->query .= "{$column}" . ($not ? ' NOT ' : ' ') . "BETWEEN {$valueOne} AND {$valueTwo}";
    }

    private function checkTable(?string &$table): void
    {
        $table = empty($table) ? $this->table : $table;
    }

    private function checkColumn(string &$column, string $table): void
    {
        $column = "{$table}.{$column}";
    }

    private function checkTableAndColumn(?string &$table, string &$column): void
    {
        $this->checkTable($table);
        $this->checkColumn($column, $table);
    }

    private function getValue(mixed $value, string &$column): string
    {
        $newValue = '';

        if (is_array($value)) {
            $newValue = "{$value[0]}.{$value[1]}";
        } elseif (is_string($value)) {
            $newValue = "'{$value}'";
        } elseif (is_numeric($value)) {
            $newValue = (string) $value;
        }

        $newValue = $this->getPrepareValue($column, $newValue);

        if (method_exists(SqlFunctions::class, $column)) {
            $column = '';
        }

        return $newValue;
    }

    private function addSeparator(string &$value, string $separator): void
    {
        if (!empty($value)) {
            $value .= ' ' . strtoupper($separator) . ' ';
        }
    }
}
