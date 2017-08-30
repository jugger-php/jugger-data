<?php

use PHPUnit\Framework\TestCase;

use jugger\data\Paginator;

class PaginatorTest extends TestCase
{
    public function testInit()
    {
        $pager = new Paginator(123, 456);
        $this->assertEquals($pager->getTotalCount(), 456);

        $pager = new Paginator(5, 23);
        $this->assertEquals($pager->getPageNow(), 1);
        $this->assertEquals($pager->getPageSize(), 5);
        $this->assertEquals($pager->getPageMax(), 5);
        $this->assertEquals($pager->getOffset(), 0);

        $pager = new Paginator(5, 23);
        $pager->setPageNow(2);
        $this->assertEquals($pager->getPageNow(), 2);
        $this->assertEquals($pager->getPageSize(), 5);
        $this->assertEquals($pager->getOffset(), 5); // 2-я страница
        $this->assertEquals($pager->getPageMax(), 5);

        $pager = new Paginator(456, 789);
        $pager->setPageNow(123);
        $this->assertEquals($pager->getPageNow(), 123);
        $this->assertEquals($pager->getPageSize(), 456);
        $this->assertEquals($pager->getOffset(), 456); // 2-я страница
        $this->assertEquals($pager->getPageMax(), 2);
    }

    /**
     * @depends testInit
     */
    public function testBase()
    {
        $pager = new Paginator(10, 4);
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 1);

        $pager = new Paginator(10, 10);
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 1);

        $pager = new Paginator(10, 51);
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 6);

        $pager = new Paginator(10, 100);
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 10);

        $pager->setPageNow(2);
        $this->assertEquals($pager->getOffset(), 10);
        $this->assertEquals($pager->getPageMax(), 10);

        $pager->setPageNow(123);
        $this->assertEquals($pager->getOffset(), 90);
        $this->assertEquals($pager->getPageMax(), 10);

        $pager->setPageNow(-123);
        $this->assertEquals($pager->getOffset(), 0);
        $this->assertEquals($pager->getPageMax(), 10);
    }
}
