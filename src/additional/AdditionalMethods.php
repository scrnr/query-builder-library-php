<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Additional;

use Scrnr\QueryBuilder\Sql\SqlFunctions;

trait AdditionalMethods
{
    private function getPrepareValue(string $column, mixed $value): string
    {
        $column = ':' . str_replace('.', '_', $column);

        $countPrepare = array_reduce(array_keys($this->preparedColumns), function (int $carry, string $prepareColumn) use ($column): int {
            if (preg_match("#^ {$column} (\d+)? $#sx", $prepareColumn)) {
                return ++$carry;
            }

            return $carry;
        }, 0);

        $column = $countPrepare > 0 ? ($column . (string) ++$countPrepare) : $column;
        $this->preparedColumns[$column] = (string) $value;

        return $column;
    }

    private function addSeparator(string &$value, string $separator): void
    {
        if (!empty($value)) {
            $value .= match ($separator) {
                ',' => ', ',
                ' ' => ' ',
                default => ''
            };
        }
    }

    private function getSqlFunciton(?string $funcName, string $column, string $table, ?string $alias): string|false
    {
        $sql = '';

        if (is_null($funcName)) {
            return false;
        }

        if (method_exists(SqlFunctions::class, $funcName)) {
            $sql = call_user_func([SqlFunctions::class, $funcName], $column, $table, $alias);
        }

        return !empty($sql) ? $sql : false;
    }
}
