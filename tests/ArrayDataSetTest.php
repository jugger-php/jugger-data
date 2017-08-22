<?php

namespace tests;

use PHPUnit\Framework\TestCase;

use jugger\data\Sorter;
use jugger\data\Paginator;
use jugger\data\driver\ArrayDataSet;
use jugger\criteria\SimpleLogicCriteria;

class ArrayDataSetTest extends TestCase
{
    public function getData()
    {
        return [
            [
                'id' => 1,
                'name' => 'value',
            ],
            [
                'id' => 2,
                'name' => 'value',
            ],
            [
                'id' => 3,
                'name' => 'value',
            ],
            [
                'id' => 4,
                'name' => 'another value',
            ],
            [
                'id' => 5,
                'name' => 'what is it',
            ],
        ];
    }

    public function testInit()
    {
        $data = $this->getData();
        $sorter = new Sorter([
            'name' => Sorter::ASC,
        ]);
        $pager = new Paginator(10, count($data));
        $criteria = new SimpleLogicCriteria([
            '>id' => 0,
        ]);

        $provider = new ArrayDataSet($data);
        $provider->setSorter($sorter);
        $provider->setPaginator($pager);
        $provider->setCriteria($criteria);

        $this->assertTrue(
            $provider->getSorter() === $sorter
        );
        $this->assertTrue(
            $provider->getPaginator() === $pager
        );
        $this->assertTrue(
            $provider->getCriteria() === $criteria
        );
    }

    public function testSorter()
    {
        $provider = new ArrayDataSet(
            $this->getData()
        );
        $provider->setSorter(
            new Sorter([
                'name' => Sorter::ASC,
                'id' => Sorter::DESC,
            ])
        );
        $data = $provider->getData();

        $this->assertEquals(5, count($data));
        $this->assertEquals($data[0]['id'], 4);
        $this->assertEquals($data[1]['id'], 3);
        $this->assertEquals($data[2]['id'], 2);
        $this->assertEquals($data[3]['id'], 1);
        $this->assertEquals($data[4]['id'], 5);
    }

    public function testPager()
    {
        $data = $this->getData();
        $provider = new ArrayDataSet($data);
        $provider->setPaginator(
            new Paginator(2, count($data))
        );
        $provider->getPaginator()->setPageNow(2);
        $data = $provider->getData();

        $this->assertEquals(2, count($data));
        $this->assertEquals($data[0]['id'], 3);
        $this->assertEquals($data[1]['id'], 4);

        $provider->getPaginator()->setPageNow(3);
        $data = $provider->getData();

        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0]['id'], 5);
    }

    public function testSetPagerSizeMethod()
    {
        $data = $this->getData();
        $provider = new ArrayDataSet($data);
        $provider->setPaginatorPageSize(10);
        $paginator = $provider->getPaginator();

        $this->assertEquals($paginator->getPageSize(), 10);
        $this->assertEquals($paginator->getPageNow(), 1);
        $this->assertEquals($paginator->getTotalCount(), count($data));
    }

    public function testCriteria()
    {
        $data = $this->getData();
        $provider = new ArrayDataSet($data);
        $provider->setCriteria(
            new SimpleLogicCriteria([
                '>=id' => 3,
            ])
        );
        $data = $provider->getData();

        $this->assertEquals(3, count($data));
        $this->assertEquals($data[0]['id'], 3);
        $this->assertEquals($data[1]['id'], 4);
        $this->assertEquals($data[2]['id'], 5);
    }
}
