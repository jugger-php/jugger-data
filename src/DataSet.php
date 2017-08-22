<?php

namespace jugger\data;

use jugger\criteria\Criteria;

abstract class DataSet
{
    protected $data;
    protected $sorter;
    protected $criteria;
    protected $paginator;

    public function __construct($data)
    {
        $this->data = $data;
        $this->init();
    }

    protected function init()
    {

    }

    public function getData(): array
    {
        return $this->prepareData();
    }

    public function getCount(): int
    {
        return count($this->getData());
    }

    public function setCriteria(Criteria $value)
    {
        $this->criteria = $value;
    }

    public function getCriteria(): ?Criteria
    {
        return $this->criteria;
    }

    public function setPaginator(Paginator $value)
    {
        $this->paginator = $value;
    }

    public function getPaginator(): ?Paginator
    {
        return $this->paginator;
    }

    public function setPaginatorPageSize(int $pageSize)
    {
        $totalCount = $this->getTotalCount();
        $this->setPaginator(
            new Paginator($pageSize, $totalCount)
        );
    }

    public function setSorter(Sorter $value)
    {
        $this->sorter = $value;
    }

    public function getSorter(): ?Sorter
    {
        return $this->sorter;
    }

    protected function prepareData()
    {
        $data = $this->data;
        if ($this->criteria) {
            $data = $this->filter($data);
        }
        if ($this->sorter) {
            $data = $this->sort($data);
        }
        if ($this->paginator) {
            $data = $this->division($data);
        }
        return $data;
    }

    abstract protected function sort($data);

    abstract protected function filter($data);

    abstract protected function division($data);

    abstract public function getTotalCount(): int;
}
