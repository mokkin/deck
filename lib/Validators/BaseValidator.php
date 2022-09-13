<?php
declare(strict_types=1);

namespace OCA\Deck\Validators;

use Exception;
use OCA\Deck\BadRequestException;

abstract class BaseValidator {

	private const MAX_LENGTH = 255;

	/**
	 * @return array
	 */
	abstract function rules();

	/**
	 * Validate given entries
	 *
	 * @param array $data
	 * @return bool
	 */
	private function validate ($data) {
		$rules = $this->rules();

		foreach ($data as $field => $value) {
			$field_rule = $rules[$field];

			if (is_array($field_rule)) {
				foreach ($field_rule as $rule) {
					// The format for specifying validation rules and parameters follows an
					// easy {rule}:{parameters} formatting convention. For instance the
					// rule "Max:3" states that the value may only be three letters.
					if (strpos($rule, ':') !== false) {
						[$rule, $parameter] = explode(':', $rule, 2);
						if (!$this->{$rule}($value, $parameter)) {
							throw new BadRequestException(
								$this->getErrorMessage($rule, $field, $parameter));
						}

					} else {
						if (!$this->{$rule}($value)) {
							throw new BadRequestException(
								$field . ' must be provided and must be '. str_replace("_", " ", $rule));
						}
					}
				}
			}

			if (is_callable($field_rule) && !$field_rule($value)) {
				throw new BadRequestException($field . ' must be provided');
			}
		}

		return true;
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	public function check(array $data)
	{
		return $this->validate($data);
	}

	// TODO: check length base on the database field length
	private function checkLength ($value) {
		if (!is_string($value)) return true;
		return strlen($value) <= self::MAX_LENGTH;
	}

	private function numeric ($value) {
		return is_numeric($value);
	}

	private function bool ($value) {
		return is_bool($value);
	}

	private function not_false ($value) {
		return ($value !== false) && ($value !== 'false');
	}

	private function not_null ($value) {
		return !is_null($value);
	}

	private function not_empty ($value) {
		return !empty($value);
	}
	private function max ($value, $limit) {
		if (!$limit || !is_numeric($limit)) {
			throw new Exception("Validation rule max requires at least 1 parameter. " . json_encode($limit));
		}
		return $this->getSize($value) <= $limit;
	}

	private function min ($value, $limit) {
		if (!$limit || !is_numeric($limit)) {
			throw new Exception("Validation rule max requires at least 1 parameter.");
		}
		return $this->getSize($value) >= $limit;
	}

	/**
     * Get the size of an attribute.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function getSize($value)
    {
        // This method will determine if the attribute is a number or string and
        // return the proper size accordingly. If it is a number, then number itself
        // is the size.
        if (is_numeric($value)) {
            return $value;
        } elseif (is_array($value)) {
            return count($value);
        }

        return mb_strlen($value ?? '');
    }

	protected function getErrorMessage($rule, $field, $parameter = null) {
		if (in_array($rule, ['max', 'min'])) {
			return $rule === 'max'
			? $field . ' cannot be longer than '. $parameter . ' characters '
			: $field . ' must be at least '. $parameter . ' characters long ';
		}

		return $field . ' must be provided and must be '. str_replace("_", " ", $rule);
	}
}
