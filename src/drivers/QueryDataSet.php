<?php

namespace jugger\data\drivers;

use jugger\db\Query;

/**
 * Набор данных для объекта запроса
 */
class QueryDataSet extends ArrayDataSet
{
    protected function prepareData()
    {
        $query = parent::prepareData();
        return $query->all();
    }

    protected function filter(Filter $filter, $query)
    {
        $filters = $filter->getFilters();
        foreach ($filters as $column => $data) {
            list($operator, $value) = $data;
            $query->andWhere([
                $operator.$column => $value
            ]);
        }
        return $query;
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
            if (in_array($sort, $ascSort)) {
                $orders[$column] = 'ASC';
            }
            elseif (in_array($sort, $descSort)) {
                $orders[$column] = 'DESC';
            }
        }
        return $query->orderBy($orders);
    }
}
