<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Commands;

use Scrnr\QueryBuilder\Additional\AdditionalMethods;
use Scrnr\QueryBuilder\Conditions\Group;
use Scrnr\QueryBuilder\Conditions\Join;
use Scrnr\QueryBuilder\Conditions\Limit;
use Scrnr\QueryBuilder\Conditions\Order;
use Scrnr\QueryBuilder\Conditions\SelectColumns;
use Scrnr\QueryBuilder\Conditions\Union;
use Scrnr\QueryBuilder\Conditions\Where;
use Scrnr\QueryBuilder\Conditions\Having;

class Select extends BaseMethods
{
    use AdditionalMethods;
    use Group;
    use Limit;
    use Order;
    use SelectColumns;
    use Union;

    public string $join = '';

    protected string $command = 'SELECT';
    protected string $distinct = '';
    protected string $from = '';

    public function __construct(string $tableName)
    {
        $this->mainTable = $tableName;
        $this->from = "FROM {$tableName}";
    }

    public function where(): Where
    {
        return new Where($this, $this->mainTable);
    }

    public function having(): Having
    {
        return new Having($this, $this->mainTable);
    }

    public function join(string $joinTable, string $type = Join::INNER): Join
    {
        return new Join($this, $this->mainTable, $joinTable, $type);
    }

    public function sqlFunctions(string $functionName, string $column, ?string $table = null, ?string $alias = null): static
    {
        if (empty($table)) {
            $this->checkTableName();
            $table = $this->additionalTable;
        }

        $sql = $this->getSqlFunciton($functionName, $column, $table, $alias);

        if ($sql !== false) {
            $this->addSeparator($this->columns, ',');
            $this->columns .= $sql;
        }

        return $this;
    }

    public function distinct(): static
    {
        $this->distinct = 'DISTINCT';

        return $this;
    }

    public function from(string $tableName): static
    {
        $this->additionalTable = $tableName;

        return $this;
    }

    public function resetTable(): static
    {
        $this->additionalTable = $this->mainTable;

        return $this;
    }

    protected function getCommandList(): array
    {
        $commandList = ['union', 'command', 'distinct', 'columns', 'from', 'join', 'where', 'group', 'having', 'order', 'limit'];

        return $commandList;
    }
}
