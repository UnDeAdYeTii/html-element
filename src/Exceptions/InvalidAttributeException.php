<?php

namespace YeTii\HtmlElement\Exceptions;

use Exception;

class InvalidAttributeException extends Exception
{
    protected $message = 'Invalid attribute for Element';

    protected $code = 8001;
}
