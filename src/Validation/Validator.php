<?php


namespace App\Validation;


use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{

    protected $errors;

    public function validate(ServerRequestInterface $request, array $rules) {

        $params = $request->getParsedBody() ?? $request->getQueryParams();

        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($params[$field]);
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getFullMessage();
            }
        }

        return $this;
    }

    public function fail() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

}