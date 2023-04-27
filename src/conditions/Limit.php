<?php

declare(strict_types=1);

namespace Scrnr\QueryBuilder\Conditions;

trait Limit
{
    protected string $limit = '';

    public function limit(?int $quantity = null, ?int $offset = null, bool $needOffset = false): static
    {
        $quantity = $this->getPrepareValue('limit', $quantity);

        $offset = (!empty($offset) or $needOffset) ? $this->getPrepareValue('offset', $offset) : '';

        $this->limit = 'LIMIT' . (empty($offset) ? ' ' : " {$offset}, ") . $quantity;

        return $this;
    }
}
