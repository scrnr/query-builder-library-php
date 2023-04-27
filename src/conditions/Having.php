<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Conditions;

use Scrnr\QueryBuilder\Commands\Delete;
use Scrnr\QueryBuilder\Commands\Select;
use Scrnr\QueryBuilder\Commands\Update;

class Having extends WhereCondition
{
    public function end(): Delete | Select | Update
    {
        $this->refererClass->having = "HAVING {$this->query}";

        return parent::end();
    }
}
