<?php

namespace jugger\data\drivers;

/**
 * Набор данных для массива
 */
class ObjectDataSet extends ArrayDataSet
{
    protected function prepareData()
    {
        foreach ($this->dataAll as & $item) {
            $item = (array) $item;
        }

        return parent::prepareData();
    }
}
