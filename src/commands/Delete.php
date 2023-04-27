<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Commands;

use Scrnr\QueryBuilder\Conditions\Where;

class Delete extends BaseMethods
{
    public function __construct(string $tableName)
    {
        $this->mainTable = $tableName;
        $this->command = "DELETE FROM {$tableName}";
    }

    public function where(): Where
    {
        return new Where($this, $this->mainTable);
    }

    protected function getCommandList(): array
    {
        $commandList = ['command', 'where'];

        return $commandList;
    }
}
