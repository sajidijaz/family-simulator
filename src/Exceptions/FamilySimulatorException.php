<?php


namespace App\Exceptions;


class FamilySimulatorException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }



}
