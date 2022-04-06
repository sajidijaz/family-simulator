<?php


namespace App\Exceptions;


class TwigRenderException extends \Exception
{
    private const ERROR_MESSAGE = 'Error while rendering Twig file: %s';

    public function __construct(string $message, int $code)
    {
        parent::__construct(
            sprintf(self::ERROR_MESSAGE, $message),
            $code
        );
    }



}
