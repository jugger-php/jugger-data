<?php

namespace jugger\data\drivers;

use jugger\data\Filter;
use jugger\data\Sorter;
use jugger\data\DataSet;
use jugger\data\Paginator;

/**
 * Набор данных для массива
 */
class ArrayDataSet extends DataSet
{
    protected function filter(Filter $filter, array $data)
    {
        $filters = $filter->getFilters();

        return array_filter($data, function($item) use($filters) {
            foreach ($filters as $column => $columnFilters) {
                $value = $item[$column];
                foreach ($columnFilters as $data) {
                    $operator = $data[0];
                    $etalon = $data[1];

                    if (!$this->filterOperation($operator, $value, $etalon)) {
                        return false;
                    }
                }
            }
            return true;
        });
    }

    protected function filterOperation($operator, $a, $b)
    {
        $ret = true;
        switch ($operator) {
            case '=':
                $ret = $a == $b;
                break;
            case '!':
                $ret = $a != $b;
                break;
            case '@':
                $ret = in_array($a, $b);
                break;
            case '%':
                $ret = strpos($a, $b);
                break;
            case '>':
                $ret = $a > $b;
                break;
            case '>=':
                $ret = $a >= $b;
                break;
            case '<':
                $ret = $a < $b;
                break;
            case '<=':
                $ret = $a <= $b;
                break;
        }
        return $ret;
    }

    protected function division(Paginator $paginator, array $data)
    {
        $offset = $paginator->getOffset();
        $limit = $paginator->getPageSize();

        return array_slice($data, $offset, $limit);
    }

    protected function sort(Sorter $sorter, array $data)
    {
        $columns = $sorter->getColumns();
        usort($data, function($a, $b) use($columns) {
            $ret = 0;
            foreach ($this->columns as $column => $sort) {
                $ret = $this->sortOperation($sort, $a[$column], $b[$column]);
                if ($ret != 0) {
                    break;
                }
            }
            return $ret;
        });

        return $data;
    }

    public function sortOperation($sort, $a, $b)
    {
        $ret = 0;
        if (is_callable($sort)) {
            $ret = call_user_func_array($sort, [$a, $b]);
        }
        elseif ($sort === self::ASC) {
            $ret = strcmp($a, $b);
        }
        elseif ($sort === self::ASC_NAT) {
            $ret = strnatcmp($a, $b);
        }
        elseif ($sort === self::DESC) {
            $ret = -strcmp($a, $b);
        }
        elseif ($sort === self::DESC_NAT) {
            $ret = -strnatcmp($a, $b);
        }
        return $ret;
    }
}
