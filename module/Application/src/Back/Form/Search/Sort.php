<?php

namespace Application\Back\Form\Search;

/**
 * Class Sort
 * @package Application\Back\Form\Search
 */
class Sort
{
    protected $columnSort = 'id';
    protected $order = 'DESC';

    /**
     * @param $columnSort
     * @param $order
     * @return array
     */
    public function getSortValue($columnSort, $order){
        if (null !== ($columnSort = $this->checkSortData($columnSort))){
            $this->columnSort =  $columnSort;
        }

        if (null !== ($order = $this->checkSortData($order))){
            $this->order =  $order;
        }

        return ["$this->columnSort" => $this->order];
    }

    /**
     * @param $data
     * @return mixed
     */
    public function checkSortData($data){
        if (isset($data) && (false === empty($data))){

            return $data;
        }
    }
}