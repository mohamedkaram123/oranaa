<?php


namespace App\Models\Filters\QueryFiltersClasses;


class WhereNotNull extends Where
{
    public function __construct(string $column, $ask_check)
    {
        parent::__construct($column, $ask_check);
    }

    public function applyFilter($builder)
    {
        return $builder->whereNotNull($this->column);
    }
}
