<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Conditions;

abstract class OrderBy
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';
}

trait Order
{
    protected string $order = '';

    public function order(?string $column = null, string $direction = OrderBy::ASC, ?string $table = null, ?string $sqlFunction = null): static
    {
        if (empty($table)) {
            $this->checkTableName();
            $table = $this->additionalTable;
        }

        if (!empty($column)) {
            $sql = $this->getSqlFunciton($sqlFunction, $column, $table, null);

            if ($sql !== false) {
                $column = $sql;
            } else {
                $column = "{$table}.{$column}";
            }
        }

        $column = $this->getPrepareValue('order', $column);

        if (empty($this->order)) {
            $this->order = "ORDER BY {$column} {$direction}";
        } else {
            $this->addSeparator($this->order, ',');
            $this->order .= "{$column} {$direction}";
        }

        return $this;
    }
}
