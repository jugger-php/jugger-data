<?php

namespace jugger\data\dataset;

/**
 * фильтр
 * объект который содержит в себе список фильтров
 */
class Filter
{
    protected $filters = [];

    public function getFilters()
    {
        return $this->filters;
    }

    public function __construct(array $filters = [])
    {
        foreach ($filters as $column => $value) {
            $this->add($column, $value);
        }
    }

    public function add($column, $value)
    {
        list($column, $operator) = $this->parseColumnName($column);

        $filters = $this->filters[$column] ?? [];
        $filters[] = [$operator, $value];
        $this->filters[$column] = $filters;
    }

    protected function parseColumnName($columnName)
    {
        $re = '/^((?:>=|<=)|(?:=|!|@|%|<|>))(.+)$/';
        if (preg_match($re, $columnName, $m) === 1) {
            return [$m[2], $m[1]];
        }
        else {
            return [$columnName, '='];
        }
    }
}
