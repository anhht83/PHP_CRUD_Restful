<?php

namespace App\controllers;

use App\repositories\Stock;

class StockController extends ApiController {
    public function get() {
        $Stock = new Stock();
        $items = $Stock->fetch();
        usort($items, function ($item1, $item2) {
            return $item1->createdAt < $item2->createdAt;
        });
        $data = [];
        $total = 0;
        foreach ($items as $item) {
            $total += (float) $item->total;
            $data[] = $item->getAttributes();
        }
        $this->response(['data' => $data, 'total' => $total]);
    }

    public function save() {
        $request = $this->request();
        $stock = new Stock($request);
        $stock = $stock->save();
        $this->response($stock->getAttributes());
    }
}
