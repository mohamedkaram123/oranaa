<?php


namespace App\Models\Filters\QueryFiltersClasses;


class WhereNull extends Where
{
    public function __construct(string $column, $ask_check)
    {
        parent::__construct($column, $ask_check);
    }

    public function applyFilter($builder)
    {
        return $builder->whereNull($this->column);
    }
}
