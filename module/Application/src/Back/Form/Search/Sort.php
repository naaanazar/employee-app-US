<?php

namespace Application\Back\Form\Search;

/**
 * Class Sort
 * @package Application\Back\Form\Search
 */
class Sort
{

    /**
     * @param $columnSort
     * @param $order
     * @return array|bool
     */
    public function getSortValue($columnSort, $order)
    {
        if (false === empty($columnSort) && false === empty($order)) {
            return [$columnSort => $order];
        }

        return false;
    }
}