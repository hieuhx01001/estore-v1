<?php
namespace frontend\models\exceptions;

class ProductNotFoundException extends \Exception
{
    const DEFAULT_MESSAGE = 'Product not found';

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        // If exception message is not given, use DEFAULT_MESSAGE
        if (empty($message)) {
            $message = static::DEFAULT_MESSAGE;
        }

        parent::__construct($message, $code, $previous);
    }
}
