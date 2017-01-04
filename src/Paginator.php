<?php

namespace jugger\data;

/**
 * пагинатор
 * хранит в себе информацию о разбивке данных на страницы
 */
class Paginator
{
    /**
     * текущая страница
     * @var integer
     */
    public $pageNow;
    /**
     * количество элементов на странице
     * @var integer
     */
    public $pageSize;
    /**
     * общее количество элементов
     * @var integer
     */
    public $totalCount;

    public function __construct($pageSize, $pageNow = 1)
    {
        $this->pageNow = $pageNow;
        $this->pageSize = $pageSize;
    }

    public function getOffset()
    {
        $p = (int) $this->pageNow;
        $pm = $this->getPageMax();

        if ($p < 1) {
            $p = 1;
        }
        elseif ($p > $pm) {
            $p = $pm;
        }

        return ($p - 1) * $this->getPageSize();
    }

    public function getPageSize()
    {
        return (int) $this->pageSize;
    }

    public function getPageMax()
    {
        if (!$this->totalCount) {
            throw new \Exception("Property 'totalCount' is required");
        }

        $t = (int) $this->totalCount;
        $ps = $this->getPageSize();

        if ($t < $ps) {
            return 1;
        }

        $x = $t % $ps ? 1 : 0;
        return intval($t / $ps) + $x;
    }
}
