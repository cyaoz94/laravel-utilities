<?php


namespace Cyaoz94\LaravelUtilities\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilter
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Converts snake_case to camelCase
     * @param $input
     * @return string
     */
    protected function snakeToCamelCase($input)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
    }

    /**
     * loops through the associative array returned by filters() and
     * if the inheriting class has a method with the name of the request key,
     * it executes that method, passing it a parameter with the request value if exists.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            $methodName = $this->snakeToCamelCase($name);
            if (!method_exists($this, $methodName)) {
                continue;
            }
            if (strlen($value)) {
                $this->$methodName($value);
            }
        }

        return $this->builder;
    }

    /**
     * returns an associative array contain input values in the url and body of the request
     * it’ll contain query strings and their values, as well as key-values within the request body
     *
     * @return array
     */
    public function filters()
    {
        return $this->request->all();
    }


    /*
|--------------------------------------------------------------------------
| Base where operations
|--------------------------------------------------------------------------
|
| Here is where you can find basic operations built into the query filter
|
| In case of complex queries, consider overriding in inheriting class
| Or handle using stored procedures
|
 */
    protected function equal($key, $value)
    {
        return $this->builder->where($key, '=', $value);
    }

    protected function like($key, $value)
    {
        return $this->builder->where($key, 'LIKE', "%$value%");
    }

    protected function ge($key, $value)
    {
        return $this->builder->where($key, '>=', $value);
    }

    protected function gt($key, $value)
    {
        return $this->builder->where($key, '>', $value);
    }

    protected function le($key, $value)
    {
        return $this->builder->where($key, '<=', $value);
    }

    protected function lt($key, $value)
    {
        return $this->builder->where($key, '<', $value);
    }

    protected function in($key, $value)
    {
        return $this->builder->whereIn($key, $value);
    }

    protected function isNotNull($key, $value)
    {
        if (boolval($value)) {
            $this->builder->whereNotNull($key);
        } else {
            $this->builder->whereNull($key);
        }

        return $this->builder;
    }

    protected function order($key, $value)
    {
        $availableValues = ['asc', 'desc'];

        if ($value == '' || !in_array($value, $availableValues)) {
            $value = 'asc';
        }

        return $this->builder->orderBy($key, $value);
    }

    protected function jsonContains($key, $value)
    {
        return $this->builder->whereJsonContains($key, $value);
    }
}
