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
     * общие количество записей с учетом фильтрации
     * @var int
     */
    private $_totalCount;
    /**
     * конструктор
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->dataAll = $data;
    }
    /**
     * подготовленные данные
     */
    public function getData(): array
    {
        if (!$this->data) {
            $this->data = $this->prepareData();
        }
        return $this->data;
    }
    /**
     * кол-во элементов на текущей странице
     * @return integer
     */
    public function getCount()
    {
        return count($this->getData());
    }
    /**
     * количества записей после фильтрации
     * @param  mixed $data данные после фильтрации
     */
    abstract protected function getInternalTotalCount($data);
    /**
     * общее количество элементов
     * @return integer
     */
    public function getTotalCount(): int
    {
        return $this->_totalCount;
    }
    /**
     * формирует данные
     */
    protected function prepareData()
    {
        $data = $this->dataAll;

        if ($this->sorter) {
            $data = $this->sort($this->sorter, $data);
        }

        $this->_totalCount = $this->getInternalTotalCount($data);

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
     * Разбивает данные на страницы
     * @param  Paginator    $paginator  объект пагинатора
     * @param  mixed        $data       данные
     * @return mixed                    одна, указанная страница данных
     */
    abstract protected function division(Paginator $paginator, $data);
}
