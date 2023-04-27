<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder;

use Scrnr\QueryBuilder\Commands\Select;
use Scrnr\QueryBuilder\Commands\Delete;
use Scrnr\QueryBuilder\Commands\Update;
use Scrnr\QueryBuilder\Commands\Insert;

class QueryBuilder
{
    public function select(string $tableName): Select
    {
        return new Select($tableName);
    }

    public function delete(string $tableName): Delete
    {
        return new Delete($tableName);
    }

    public function insert(string $tableName): Insert
    {
        return new Insert($tableName);
    }

    public function update(string $tableName): Update
    {
        return new Update($tableName);
    }
}
