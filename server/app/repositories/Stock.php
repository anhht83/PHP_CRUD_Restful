<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/18/2018
 * Time: 11:46 PM
 */

namespace App\repositories;

class Stock extends Base {
    protected $filled = ['id', 'product_name', 'quantity', 'price', 'createdAt'];
    protected $append = ['total'];

    public function getAttributeTotal() {
        return (float)$this->price * (int)$this->quantity;
    }

    public function getAttributeCreatedAt() {
        return date('m-d-Y', $this->createdAt);
    }
}