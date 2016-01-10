<?php

namespace Uny\Examples;

class ModelCollection implements \ArrayAccess, \IteratorAggregate, \Serializable, \Countable {

	private $container = [];

    public function __construct() {
        
    }

    public function setId($id){
    	foreach($this->container as $item){
    		$item->setId($id);
    	}
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function getIterator() {
        return new \ArrayIterator($this->container);
    }

    public function serialize() {
        return serialize($this->container);
    }

    public function unserialize($data) {
        $this->container = unserialize($data);
    }

    public function count() { 
        return count($this->container); 
    }

}