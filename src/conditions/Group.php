<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Conditions;

trait Group
{
    protected string $group = '';

    public function group(string $column, ?string $table = null): static
    {
        if (empty($table)) {
            $this->checkTableName();
            $table = $this->additionalTable;
        }

        $value = "{$table}.{$column}";

        if (empty($this->group)) {
            $this->group = "GROUP BY {$value}";
        } else {
            $this->addSeparator($this->group, ',');
            $this->group .= $value;
        }

        return $this;
    }
}
