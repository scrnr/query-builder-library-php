<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Conditions;

trait Union
{
    protected string $union = '';

    public function union(string $tableName): static
    {
        $this->createUnion($tableName, 'UNION');

        return $this;
    }

    public function unionAll(string $tableName): static
    {
        $this->createUnion($tableName, 'UNION ALL');

        return $this;
    }

    private function createUnion(string $tableName, string $union): void
    {
        $commandList = $this->getCommandList();

        foreach ($commandList as $command) {
            if (empty($this->$command) or $command === 'union') {
                continue;
            }

            $this->addSeparator($this->union, ' ');

            $this->union .= $this->$command;

            switch ($command) {
                case 'union':
                case 'from':
                    break;
                case 'command':
                    $this->$command = 'SELECT';
                    break;
                default:
                    $this->$command = '';
                    break;
            }
        }

        $this->union .= " {$union}";

        $this->table($tableName);
    }

    private function table(string $tableName): static
    {
        $this->from = "FROM {$tableName}";
        $this->mainTable = $this->additionalTable = $tableName;

        return $this;
    }
}
