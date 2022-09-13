<?php
declare(strict_types=1);


namespace OCA\Deck\Validators;

class AssignmentServiceValidator extends BaseValidator {
	public function rules () {
		return [
			'cardId' => ['numeric'],
			'userId' => ['not_empty', 'not_null', 'not_false', 'max:64'],
		];
	}
}
