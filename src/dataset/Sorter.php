<?php

namespace jugger\data\dataset;

/**
 * сортировщик
 * хранит в себе информацию сортировке и собствено за нее отвечает
 */
class Sorter
{
    /**
     * По возрастанию
     * @var string
     */
    const ASC = 1;
    /**
     * По возрастанию натуральная сортировка
     * @var string
     */
    const ASC_NAT = 2;
    /**
     * По убыванию
     * @var string
     */
    const DESC = 3;
    /**
     * По убыванию натуральная сортировка
     * @var string
     */
    const DESC_NAT = 4;

    /**
     * столбцы, по которым происходит сортировка
     * @var array
     */
    protected $columns = [];
    /**
     * Геттер для столбцов
     * @return array 
     */
    public function getColumns()
    {
        return $this->columns;
    }
    /**
     * Конструктор
     * @param array $columns список столбцов и сортировок
     */
    public function __construct(array $columns = [])
    {
        foreach ($columns as $column => $value) {
            $this->set($column, $value);
        }
    }
    /**
     * назначение сортировки для столбца
     * @param mixed $column название столбца
     * @param integer $sort тип сортировки
     */
    public function set($column, $sort)
    {
        $types = [
            self::ASC,
            self::ASC_NAT,
            self::DESC,
            self::DESC_NAT
        ];

        if (is_callable($sort) || in_array($sort, $types)) {
            $this->columns[$column] = $sort;
        }
        else {
            throw new \Exception('Invalide sort type. Column: '.$column);
        }
    }
}
