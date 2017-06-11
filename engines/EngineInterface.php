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
     * @param int $delay How many seconds wait be executing
     * @return bool Whether get in queue successfully
     */
    public function in(string $cmd, $delay = 0): bool;

    /**
     * Combine of [[get]] and [[remove]]
     * @return mixed
     */
    public function out();

    public function get();

    public function remove($id);

}