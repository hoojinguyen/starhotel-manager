<?php

namespace Romi\Validation;

use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator {

	protected $errors = [];

	public function validate(ServerRequestInterface $request, array $rules) {
		foreach ($rules as $field => $rule) {
			try {
				$rule->setName($field)->assert($request->getParam($field));
			} catch (NestedValidationException $e) {
				$this->errors[$field] = $e->getMessages();
			}
		}
		$_SESSION['errors'] = $this->errors;
		return $this;
	}

	public function validateArray(array $values, array $rules) {
		foreach ($rules as $field => $rule) {
			try {
				$rule->setName($field)->assert($this->getValue($values, $field));
			} catch (NestedValidationException $e) {
				$this->errors[$field] = $e->getMessages();
			}
		}
		$_SESSION['errors'] = $this->errors;
		return $this;
	}

	public function failed() {
		return !empty($this->errors);
	}

	public function getErrors() {
		return $this->errors;
	}

	private function getValue($values, $field) {
		return isset($values[$field]) ? $values[$field] : null;
	}

}
