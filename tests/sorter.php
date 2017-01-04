<?php

use PHPUnit\Framework\TestCase;

use jugger\data\Sorter;

class SorterTest extends TestCase
{
    public function testInit()
    {
        $sorter = new Sorter([
            'col1' => Sorter::ASC,
            'col3' => Sorter::DESC,
            'col5' => function($a, $b) {
                return $a + $b;
            },
        ]);
        $sorter->set('col2', Sorter::ASC_NAT);
        $sorter->set('col4', Sorter::DESC_NAT);

        $columns = $sorter->getColumns();
        $this->assertEquals($columns['col1'], Sorter::ASC);
        $this->assertEquals($columns['col2'], Sorter::ASC_NAT);
        $this->assertEquals($columns['col3'], Sorter::DESC);
        $this->assertEquals($columns['col4'], Sorter::DESC_NAT);

        $col5 = call_user_func_array($columns['col5'], [1,2]);
        $this->assertEquals($col5, 3);
    }
}
