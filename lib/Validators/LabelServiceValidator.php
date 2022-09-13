<?php
declare(strict_types=1);


namespace OCA\Deck\Validators;

class LabelServiceValidator extends BaseValidator {

	public function rules () {
		return [
			'title' 	=> ['not_empty', 'not_null', 'not_false', 'max:100'],
			'boardId' 	=> ['numeric', 'not_null'],
			'color' 	=> ['not_empty', 'not_null', 'not_false', 'max:6']
		];
	}
}
