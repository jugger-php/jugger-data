<?php

use PHPUnit\Framework\TestCase;

use jugger\data\Filter;
use jugger\data\Sorter;
use jugger\data\Paginator;
use jugger\data\drivers\ArrayDataSet;
use jugger\data\drivers\QueryDataSet;
use jugger\data\drivers\ObjectDataSet;

use jugger\db\Query;
use jugger\db\driver\MysqliConnection;

class DatasetTest extends TestCase
{
    public static $db;

    public function dataSetProvider()
    {
        self::setUpBeforeClass();

        $arrayData = $this->getArrayData();
        $queryData = $this->getQueryData();

        return [
            [
                $arrayData,
                new ArrayDataSet($arrayData),
            ],
            [
                $queryData->all(),
                new QueryDataSet($queryData),
            ],
        ];
    }

    /**
     * @dataProvider dataSetProvider
     */
    public function testBase($data, $dataset)
    {
        $rows = $dataset->getData();

        $this->assertEquals(count($data), count($rows));
        for ($i=0; $i<count($data); $i++) {
            $this->assertEquals($data[$i]['id'], $rows[$i]['id']);
            $this->assertEquals($data[$i]['name'], $rows[$i]['name']);
            $this->assertEquals($data[$i]['number'], $rows[$i]['number']);
        }
    }

    /**
     * @dataProvider dataSetProvider
     */
    public function testSorter($data, $dataset)
    {
        $dataset->sorter = new Sorter([
            'name'   => Sorter::DESC_NAT,
            'id'     => Sorter::ASC,
            'number' => function($a, $b) {
                return 0;
            },
        ]);
        $rows = $dataset->getData();

        $this->assertEquals($rows[0]['id'], 9);
        $this->assertEquals($rows[1]['id'], 4);
        $this->assertEquals($rows[2]['id'], 8);
        $this->assertEquals($rows[3]['id'], 3);
        $this->assertEquals($rows[4]['id'], 7);
        $this->assertEquals($rows[5]['id'], 2);
        $this->assertEquals($rows[6]['id'], 6);
        $this->assertEquals($rows[7]['id'], 1);
        $this->assertEquals($rows[8]['id'], 5);
    }

    /**
     * @dataProvider dataSetProvider
     */
    public function testFilter($data, $dataset)
    {
        $dataset->filter = new Filter([
            '>id' => 5,
            '@name' => ['name1', 'name2', 'name3'],
            '=number' => 789
        ]);
        $rows = $dataset->getData();

        $this->assertEquals(count($rows), 1);
        $this->assertEquals($rows[0]['id'], 6);
    }

    /**
     * @dataProvider dataSetProvider
     */
    public function testPager($data, $dataset)
    {
        $dataset->sorter = new Sorter([
            'id' => Sorter::ASC,
        ]);
        $dataset->paginator = new Paginator(3, 2);
        $rows = $dataset->getData();

        $this->assertEquals($rows[0]['id'], 4);
        $this->assertEquals($rows[1]['id'], 5);
        $this->assertEquals($rows[2]['id'], 6);
    }


     public function getArrayData()
     {
         return [
             [
                 'id' => 1,
                 'name' => 'name1',
                 'number' => 123,
             ],
             [
                 'id' => 2,
                 'name' => 'name2',
                 'number' => 456,
             ],
             [
                 'id' => 3,
                 'name' => 'name3',
                 'number' => 789,
             ],
             [
                 'id' => 4,
                 'name' => 'name4',
                 'number' => 123,
             ],
             [
                 'id' => 5,
                 'name' => 'name1',
                 'number' => 456,
             ],
             [
                 'id' => 6,
                 'name' => 'name2',
                 'number' => 789,
             ],
             [
                 'id' => 7,
                 'name' => 'name3',
                 'number' => 123,
             ],
             [
                 'id' => 8,
                 'name' => 'name4',
                 'number' => 456,
             ],
             [
                 'id' => 9,
                 'name' => 'name5',
                 'number' => 789,
             ],
         ];
     }

     public function getQueryData()
     {
         return (new Query(self::$db))
             ->select(['id', 'name', 'number'])
             ->from('test_query_dataset');
     }

     public static function setUpBeforeClass()
     {
         self::$db = new MysqliConnection();
         self::$db->host = "localhost";
         self::$db->dbname = "test";
         self::$db->username = "root";
         self::$db->password = "";

         self::$db->execute("DROP TABLE IF EXISTS `test_query_dataset`");
         self::$db->execute("
         CREATE TABLE `test_query_dataset` (
             `id` INT,
             `name` TEXT,
             `number` INT
         )
         ");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(1,'name1',123)");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(2,'name2',456)");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(3,'name3',789)");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(5,'name1',456)");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(4,'name4',123)");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(6,'name2',789)");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(7,'name3',123)");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(8,'name4',456)");
         self::$db->execute("INSERT INTO `test_query_dataset` VALUES(9,'name5',789)");
     }

     public static function tearDownAfterClass()
     {
         self::$db->execute("DROP TABLE IF EXISTS `test_query_dataset`");
     }
}
