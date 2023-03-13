<?php


namespace App\Models\Filters\QueryFiltersClasses;

use App\Models\Filters\IFilter;
use Illuminate\Database\Eloquent\Builder;

class Where extends IFilter
{
    private string $operation;
    protected string $boolean;
    protected $value_or_closure;

    /**
     * Where constructor.
     * @param string $column
     * @param boolean $ask_check
     * @param string $operation
     * @param string $boolean
     * @param \Closure|string|int $value_or_closure
     */
    public function __construct(string $column, $ask_check, string $operation = '=', $value_or_closure = null, string $boolean = "AND")
    {
        parent::__construct($column, $ask_check);
        $this->operation = $operation;
        $this->boolean = $boolean;
        $this->value_or_closure = $value_or_closure ?? $ask_check;
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function applyFilter($builder)
    {
        $funk = $this->value_or_closure;
        if (is_callable($funk)) {
            $value = $funk();
        } else {
            $value = $funk;
        }
        if(request('status') == "rejected" || request('status') == "canceled"){
            return  $builder->where(function($q) {
                $q->where('status', 'canceled')
                   ->orWhere('status', 'waiting_chef_response');
                 });

        }

        return  $builder->where($this->column, $this->operation, $value, $this->boolean);
    }
}
