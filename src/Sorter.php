<?php

namespace jugger\data;

class Sorter
{
    const ASC = 1;
    const ASC_NAT = 2;
    const DESC = 3;
    const DESC_NAT = 4;

    protected $columns = [];

    public function __construct(array $columns = [])
    {
        foreach ($columns as $column => $value) {
            $this->set($column, $value);
        }
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getColumn(string $name): int
    {
        return $this->getColumns()[$name] ?? null;
    }

    public function set(string $column, $sort): void
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
