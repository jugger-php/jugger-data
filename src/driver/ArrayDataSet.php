<?php

namespace jugger\data\driver;

use jugger\data\Sorter;
use jugger\data\DataSet;
use jugger\data\Paginator;

class ArrayDataSet extends DataSet
{
    protected function init()
    {
        parent::init();
        if (is_array($this->data)
            || $this->data instanceof \ArrayAccess
            && $this->data instanceof \Iterator
            && $this->data instanceof \Countable
        ) {
            // is ok
        }
        else {
            throw new \Exception("Property 'data' must be ARRAY type or implements '\ArrayAccess', '\Countable', '\Iterator' interfaces");
        }
    }

    public function getTotalCount(): int
    {
        return count($this->data);
    }

    protected function filter($data)
    {
        throw new \Exception("Filter not success");
    }

    protected function division($data)
    {
        $offset = $this->paginator->getOffset();
        $limit = $this->paginator->getPageSize();

        return array_slice($data, $offset, $limit);
    }

    protected function sort($data)
    {
        $columns = $this->sorter->getColumns();
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

    protected function sortOperation($sort, $a, $b)
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
