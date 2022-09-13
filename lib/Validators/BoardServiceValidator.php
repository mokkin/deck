<?php
declare(strict_types=1);


namespace OCA\Deck\Validators;

class BoardServiceValidator extends BaseValidator {

	public function rules () {
		return [
			'id'		 	=> ['numeric'],
			'mapper' 	 	=> ['not_empty', 'not_null', 'not_false'],
			'title' 	 	=> ['not_empty', 'not_null', 'not_false', 'max:100', 'min:5'],
			'userId' 	 	=> ['not_empty', 'not_null', 'not_false', 'max:64'],
			'color' 	 	=> ['not_empty', 'not_null', 'not_false', 'max:6'],
			'participant'	=> ['not_empty', 'not_null', 'not_false', 'max:64'],
			'edit' 			=> ['not_null'],
			'share'			=> ['not_null'],
			'manage'		=> ['not_null'],
			'archived'		=> ['bool']
		];
	}
}
