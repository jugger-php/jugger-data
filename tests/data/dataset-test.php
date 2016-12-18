<?php

use PHPUnit\Framework\TestCase;

use jugger\data\Filter;
use jugger\data\Sorter;
use jugger\data\Paginator;
use jugger\data\ArrayDataSet;

class DatasetTest extends TestCase
{
    public function getData()
    {
        return [
            [1,'name1',123],
            [2,'name2',456],
            [3,'name3',789],
            [4,'name4',123],
            [5,'name1',456],
            [6,'name2',789],
            [7,'name3',123],
            [8,'name4',456],
            [9,'name5',789],
        ];
    }

    public function testBase()
    {
        $data = $this->getData();
        $dataset = new ArrayDataSet($data);
        $rows = $dataset->getData();

        $this->assertEquals(count($data), count($rows));
        for ($i=0; $i<count($data); $i++) {
            $this->assertEquals($data[$i][0], $rows[$i][0]);
            $this->assertEquals($data[$i][1], $rows[$i][1]);
            $this->assertEquals($data[$i][2], $rows[$i][2]);
        }
    }

    public function testSorter()
    {
        $data = $this->getData();
        $dataset = new ArrayDataSet($data);
        $dataset->sorter = new Sorter([
            0 => Sorter::ASC,
            1 => Sorter::DESC_NAT,
            2 => function($a, $b) {
                return 0;
            },
        ]);
        $rows = $dataset->getRows();
    }

    public function testFilter()
    {
        $data = $this->getData();
        $dataset = new ArrayDataSet($data);
        $dataset->filter = new Filter();
        $rows = $dataset->getRows();
    }

    public function testPager()
    {
        $data = $this->getData();
        $dataset = new ArrayDataSet($data);
        $dataset->pager = new Pager(2, 1);
        $rows = $dataset->getRows();

    }
}
