<?php

namespace App\repositories;
abstract class Base {
    private $values = [];
    protected $filled = [];
    protected $append = [];

    protected $dataFile = './data/stock.json';

    public function __construct($attributes = false) {
        if ($attributes) {
            $this->setAttributes($attributes);
        }
    }

    private function getDataFromFile() {
        $items = file_get_contents($this->dataFile);
        return json_decode($items);
    }

    // find a list of rows
    public function fetch() {
        $items = $this->getDataFromFile();
        if ($items) {
            $entities = [];
            foreach ($items as $item) {
                $currentClass = get_class($this);
                $entities[] = new $currentClass($item);
            }
            return $entities;
        } else {
            return [];
        }
    }

    // find by id
    public function find($id) {
        $items = $this->getDataFromFile();
        if ($items) {
            return array_filter($items, function ($item) use ($id) {
                return $item['id'] === $id;
            });
        } else {
            return false;
        }
    }

    // save entity
    public function save() {
        $items = $this->getDataFromFile();
        $this->createdAt = date('m-d-Y');
        $this->id = uniqid();
        $items[] = $this->getAttributes();
        file_put_contents($this->dataFile, json_encode($items));
        return $this;
    }


    // magic methods
    public function __get($attribute) {
        if (in_array($attribute, $this->filled) && isset($this->values[$attribute])) {
            return $this->values[$attribute];
        }

        if (in_array($attribute, $this->append) && method_exists($this, 'getAttribute' . ucfirst($attribute))) {
            return $this->{'getAttribute' . ucfirst($attribute)}();
        }

        return null;
    }

    public function __set($attribute, $value) {
        if (in_array($attribute, $this->filled)) {
            $this->values[$attribute] = $value;
        }
    }

    public function setAttributes($attributes) {
        foreach ($attributes as $attribute => $value) {
            $this->__set($attribute, $value);
        }
    }

    // get all attributes
    public function getAttributes() {
        $attributes = [];
        foreach ($this->filled as $attribute) {
            $attributes[$attribute] = $this->{$attribute};
        }
        foreach ($this->append as $attribute) {
            $attributes[$attribute] = $this->{$attribute};
        }
        return $attributes;
    }
}