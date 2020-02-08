<?php
declare(strict_types=1);

namespace SimpleAcl\Interfaces;

interface CollectionInterface extends \Countable, \IteratorAggregate {
    
    /**
     * Remove an item with the specified key from the collection if it exists and return the removed item or null if it doesn't exist.
     * 
     * @param string|int $key
     * 
     * @return mixed the removed item
     */
    public function removeByKey($key);
    
    /**
     * Check if specified key exists in the collection.
     * 
     * @param string|int $key
     * 
     * @return bool
     */
    public function keyExists($key): bool;
}
