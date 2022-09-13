<?php
declare(strict_types=1);


namespace OCA\Deck\Validators;

class AttachmentServiceValidator extends BaseValidator {
	public function rules () {
		return [
			'cardId' 	=> ['numeric'],
			'type' 		=> ['not_empty', 'not_null', 'not_false'],
			'data' 		=> ['not_empty', 'not_null', 'not_false', 'max:255'],
		];
	}
}
