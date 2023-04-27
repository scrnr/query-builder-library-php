<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Sql;

abstract class SqlFuncList
{
    public const COUNT = 'count';
    public const SUM = 'sum';
    public const AVG = 'avg';
    public const MIN = 'min';
    public const MAX = 'max';
}
