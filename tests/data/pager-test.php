<?php

use PHPUnit\Framework\TestCase;

use jugger\data\Paginator;

class PaginatorTest extends TestCase
{
    public function testInit()
    {
        $pager = new Paginator(5);
        $this->assertEquals($pager->pageNow, 1);
        $this->assertEquals($pager->pageSize, 5);
        $this->assertEquals($pager->totalCount, null);

        $pager = new Paginator(5, 2);
        $this->assertEquals($pager->pageNow, 2);
        $this->assertEquals($pager->pageSize, 5);
        $this->assertEquals($pager->totalCount, null);

        $pager = new Paginator(5);
        $pager->pageNow = 123;
        $pager->pageSize = 456;
        $pager->totalCount = 789;
        $this->assertEquals($pager->pageNow, 123);
        $this->assertEquals($pager->pageSize, 456);
        $this->assertEquals($pager->totalCount, 789);
    }

    /**
     * @depends testInit
     */
    public function testBase()
    {
        $pager = new Paginator(10, 1);

        $pager->totalCount = 4;
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 1);

        $pager->totalCount = 10;
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 1);

        $pager->totalCount = 100;
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 10);

        $pager->pageNow = 2;
        $this->assertEquals($pager->getOffset(), 10);
        $this->assertEquals($pager->getPageMax(), 10);

        $pager->pageNow = 123;
        $this->assertEquals($pager->getOffset(), 90);
        $this->assertEquals($pager->getPageMax(), 10);

        $pager->pageNow = -123;
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 10);

        $pager->pageNow = 'sdfljnsdlfnsjdf';
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 10);
    }
}
