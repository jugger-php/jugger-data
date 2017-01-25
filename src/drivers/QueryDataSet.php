<?php

namespace jugger\data\drivers;

use jugger\db\Query;
use jugger\data\Sorter;
use jugger\data\DataSet;
use jugger\data\Paginator;

/**
 * Набор данных для объекта запроса
 */
class QueryDataSet extends DataSet
{
    protected function getInternalTotalCount($data)
    {
        $query = clone $data;
        $row = $query->select('COUNT(*) AS `cnt`')->one();
        return (int) $row['cnt'];
    }

    protected function prepareData()
    {
        return parent::prepareData()->all();
    }

    protected function division(Paginator $paginator, $query)
    {
        $query->offset($paginator->getOffset());
        $query->limit($paginator->getPageSize());
        return $query;
    }

    protected function sort(Sorter $sorter, $query)
    {
        $orders = [];
        $sorters = $sorter->getColumns();
        $ascSort = [Sorter::ASC, Sorter::ASC_NAT];
        $descSort = [Sorter::DESC, Sorter::DESC_NAT];

        foreach ($sorters as $column => $sort) {
            if (!is_scalar($sort)) {
                continue;
            }
            elseif (in_array($sort, $ascSort)) {
                $orders[$column] = 'ASC';
            }
            elseif (in_array($sort, $descSort)) {
                $orders[$column] = 'DESC';
            }
        }
        return $query->orderBy($orders);
    }
}
