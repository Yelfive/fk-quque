<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */

namespace fk\queue\engines;

/**
 * This is engine interface for queue
 * Each engine should implement this interface to work as expected
 */
interface EngineInterface
{
    /**
     * Method accepts array as only parameter,
     * and with this parameter,
     * each class implements this interface should write to cache or db
     * the cmd line in the string form
     * @param string $cmd Cmd to be applied
     * @return bool Whether get in queue successfully
     */
    public function in(string $cmd): bool;

    public function out();

}