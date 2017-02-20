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
     * @return array
     */
    public function getSortValue($columnSort = 'id', $order = 'DESC'){
        return [$columnSort => $order];
    }
}