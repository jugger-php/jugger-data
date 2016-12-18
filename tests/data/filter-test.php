<?php

use PHPUnit\Framework\TestCase;

use jugger\data\Filter;

class FilterTest extends TestCase
{
    public function testBase()
    {
        $filter = new Filter([
            'col1' => 123,
            '>col2' => 123,
            '>=col3' => 123,
            '<col4' => 123,
            '<=col5' => 123,
        ]);
        $filter->add('!col6', function($value) {
            return intval($value) > 5;
        });
        $filter->add('@col7', [1,2,3]);
        $filter->add('%col8', 'gs');

        $filters = $filter->getFilters();

        $col = $filters['col1'][0];
        $this->assertEquals($col[0], '=');
        $this->assertEquals($col[1], 123);

        $col = $filters['col2'][0];
        $this->assertEquals($col[0], '>');
        $this->assertEquals($col[1], 123);

        $col = $filters['col3'][0];
        $this->assertEquals($col[0], '>=');
        $this->assertEquals($col[1], 123);

        $col = $filters['col4'][0];
        $this->assertEquals($col[0], '<');
        $this->assertEquals($col[1], 123);

        $col = $filters['col5'][0];
        $this->assertEquals($col[0], '<=');
        $this->assertEquals($col[1], 123);

        $col = $filters['col6'][0];
        $this->assertEquals($col[0], '!');
        $this->assertEquals(call_user_func($col[1], 10), true);

        $col = $filters['col7'][0];
        $this->assertEquals($col[0], '@');
        $this->assertEquals($col[1][0], 1);
        $this->assertEquals($col[1][1], 2);
        $this->assertEquals($col[1][2], 3);

        $col = $filters['col8'][0];
        $this->assertEquals($col[0], '%');
        $this->assertEquals($col[1], 'gs');
    }

    public function testMany()
    {
        $filter = new Filter();
        $filter->add('col', 123);
        $filter->add('col', 456);
        $filter->add('@col', [7,8,9]);
        $filter->add('!col', null);

        $filters = $filter->getFilters();
        $filters = $filters['col'];

        $data = $filters[0];
        $this->assertEquals($data[0], '=');
        $this->assertEquals($data[1], 123);

        $data = $filters[1];
        $this->assertEquals($data[0], '=');
        $this->assertEquals($data[1], 456);

        $data = $filters[2];
        $this->assertEquals($data[0], '@');
        $this->assertEquals($data[1][0], 7);
        $this->assertEquals($data[1][1], 8);
        $this->assertEquals($data[1][2], 9);

        $data = $filters[3];
        $this->assertEquals($data[0], '!');
        $this->assertEquals($data[1], null);
    }
}
