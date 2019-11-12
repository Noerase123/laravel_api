<?php
/**
 * @author Mike Alvarez <mr.devpoop@gmail.com>
 */

namespace App\Support\Repository\Contracts;

interface Repository
{
    /**
     * retrieves repository table name
     *
     * @return void
     */
    public function table();
}

