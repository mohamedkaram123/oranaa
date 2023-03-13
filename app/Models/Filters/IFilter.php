<?php


namespace App\Models\Filters;


use Illuminate\Database\Eloquent\Builder;

abstract class IFilter
{
    protected $ask_check;
    protected string $column;

    public function __construct(string $column, $ask_check)
    {
        $this->ask_check = $ask_check;
        $this->column = $column;
    }

    public final function handle($request, $next)
    {
        if ($this->ask_check === null or $this->ask_check === false) {
            return $next($request);
        }

        $builder = $this->applyFilter($request);

        return $next($builder);
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public abstract function applyFilter($builder);
}
