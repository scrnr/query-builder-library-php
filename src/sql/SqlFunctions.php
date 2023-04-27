<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Sql;

abstract class SqlFunctions
{    
    private static string $pattern = '#(.+)#';

    public static function count(string $column, string $table, ?string $alias): string
    {
        return self::getSqlFuncQuery($column, $table, 'COUNT(\1)', $alias);
    }

    public static function sum(string $column, string $table, ?string $alias): string
    {
        return self::getSqlFuncQuery($column, $table, 'SUM(\1)', $alias);
    }

    public static function avg(string $column, string $table, ?string $alias): string
    {
        return self::getSqlFuncQuery($column, $table, 'AVG(\1)', $alias);
    }

    public static function min(string $column, string $table, ?string $alias): string
    {
        return self::getSqlFuncQuery($column, $table, 'MIN(\1)', $alias);
    }

    public static function max(string $column, string $table, ?string $alias): string
    {
        return self::getSqlFuncQuery($column, $table, 'MAX(\1)', $alias);
    }

    private static function getSqlFuncQuery(string $column, string $table, string $replacement, ?string $alias): string
    {
        if ($column !== '*') {
            $column = "{$table}.{$column}";
        }

        $column = preg_replace(self::$pattern, $replacement, $column);

        $column .= !empty($alias) ? " AS {$alias}" : '';

        return $column;
    }
}
