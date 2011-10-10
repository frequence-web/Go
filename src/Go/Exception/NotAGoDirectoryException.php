<?php

namespace Go\Exception;

class NotAGoDirectoryException extends \Exception
{
    public function __construct()
    {
        parent::__construct('You are not in a go managed directory');
    }
}
