<?php

namespace jugger\data;

class Paginator
{
    protected $pageNow;
    protected $pageSize;
    protected $totalCount;

    public function __construct(int $pageSize, int $totalCount, int $pageNow = 1)
    {
        $this->pageSize = $pageSize;
        $this->totalCount = $totalCount;
        $this->pageNow = $pageNow;
    }

    public function setPageNow(int $value)
    {
        $this->pageNow = $value;
    }

    public function getPageNow(): int
    {
        return $this->pageNow;
    }

    public function getOffset(): int
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

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getPageMax(): int
    {
        $t = $this->totalCount;
        $ps = $this->getPageSize();

        if ($t < $ps) {
            return 1;
        }

        $x = $t % $ps ? 1 : 0;
        return intval($t / $ps) + $x;
    }
}
