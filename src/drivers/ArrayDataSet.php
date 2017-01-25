<?php

namespace jugger\data\drivers;

use jugger\data\Sorter;
use jugger\data\DataSet;
use jugger\data\Paginator;

/**
 * Набор данных для массива
 */
class ArrayDataSet extends DataSet
{
    protected function getInternalTotalCount($data)
    {
        return count($data);
    }

    protected function prepareData()
    {
        // сбрасываем ключи
        return array_values(parent::prepareData());
    }

    protected function division(Paginator $paginator, $data)
    {
        $offset = $paginator->getOffset();
        $limit = $paginator->getPageSize();

        return array_slice($data, $offset, $limit);
    }

    protected function sort(Sorter $sorter, $data)
    {
        $columns = $sorter->getColumns();
        usort($data, function($a, $b) use($columns) {
            $ret = 0;
            foreach ($columns as $column => $sort) {
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
        elseif ($sort === Sorter::ASC) {
            $ret = strcmp($a, $b);
        }
        elseif ($sort === Sorter::ASC_NAT) {
            $ret = strnatcmp($a, $b);
        }
        elseif ($sort === Sorter::DESC) {
            $ret = -strcmp($a, $b);
        }
        elseif ($sort === Sorter::DESC_NAT) {
            $ret = -strnatcmp($a, $b);
        }
        return $ret;
    }
}
