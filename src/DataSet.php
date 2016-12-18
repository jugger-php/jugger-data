<?php

namespace jugger\data;

/**
 * Набор данных
 * Позволяет проводить над данными операции:
 * - сортировки
 * - пагинации
 * - фильтрации
 */
abstract class DataSet
{
    /*
     * все данные
     * @var mixed
     */
    protected $dataAll;
    /**
     * выходные данные, сформированные в соответствии с пагинатором и сортировщиком
     * @var mixed
     */
    private $data;
    /**
     * сортировщик
     * хранит в себе информацию о сортируемых столбцах и способах их сортировки
     * @var Sorter
     */
    public $sorter;
    /**
     * пагинатор
     * хранит в себе информацию о разбивке данных на страницы
     * @var Paginator
     */
    public $paginator;
    /**
     * фильтр
     * хранит в себе данные об отборе данных
     * @var Filter
     */
    public $filter;
    /**
     * конструктор
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->dataAll = $data;
    }
    /**
     * возвращает подготовленные данные
     */
    public function getData()
    {
        if (!$this->data) {
            $this->data = $this->prepareData();
        }
        return $this->data;
    }
    /**
     * кол-во элементов среза
     * @return integer
     */
    public function getCount()
    {
        return count($this->getData());
    }
    /**
     * общее количество элементов
     * @return integer
     */
    public function getCountTotal()
    {
        return count($this->dataAll);
    }
    /**
     * формирует данные
     */
    protected function prepareData()
    {
        $data = $this->dataAll();

        if ($this->filter) {
            $data = $this->filter($this->filter, $data);
        }

        if ($this->sorter) {
            $data = $this->sort($this->sorter, $data);
        }

        if ($this->paginator) {
            $this->paginator->totalCount = $this->getTotalCount();
            $data = $this->division($this->paginator, $data);
        }

        return $data;
    }
    /**
     * Сортирует данные
     * @param  Sorter   $sorter объект сортировщика
     * @param  mixed    $data   данные
     * @return mixed            отсортированные данные
     */
    abstract protected function sort(Sorter $sorter, $data);
    /**
     * Фильтрует данные
     * @param  Filter   $filter объект фильтра
     * @param  mixed    $data   данные
     * @return mixed            отсортированные данные
     */
    abstract protected function filter(Filter $filter, $data);
    /**
     * Разбивает данные на страницы
     * @param  Paginator    $paginator  объект пагинатора
     * @param  mixed        $data       данные
     * @return mixed                    одна, указанная страница данных
     */
    abstract protected function division(Paginator $paginator, $data);
}
