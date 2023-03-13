<?php


namespace App\Models\Filters\QueryFiltersClasses;

use App\Models\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class WhereIn extends IFilter
{
    private  $values = [];
    private string $boolean;

    public function __construct(string $column, $ask_check,  $values = null, string $boolean = "AND")
    {
        parent::__construct($column, $ask_check);
        $this->values = $values ?? $ask_check;
        $this->boolean = $boolean;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function applyFilter($builder)
    {
        return $builder->whereIn($this->column, $this->values, $this->boolean);
    }
}
