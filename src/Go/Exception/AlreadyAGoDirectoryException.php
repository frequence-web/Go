<?php

namespace Go\Exception;

use Go\Exception;

class AlreadyAGoDirectoryException extends Exception
{
    public function __construct()
    {
        parent::__construct('The current directory is already managed by Go');
    }
}
