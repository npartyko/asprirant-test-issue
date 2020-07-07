<?php


namespace App\Validation\Exceptions;



use Respect\Validation\Exceptions\ValidationException;

class ExistsRuleException extends ValidationException
{

    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must be exists',
        ]
    ];


}