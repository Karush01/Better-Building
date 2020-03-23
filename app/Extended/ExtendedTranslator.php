<?php

namespace App\Extended;

use Illuminate\Translation\Translator;

class ExtendedTranslator extends Translator {
	public function get( $key, array $replace = [], $locale = null, $fallback = true ) {
		$key = mb_strtolower( $key );

		return parent::get( $key, $replace, $locale, $fallback );
	}

	public function getFromJson( $key, array $replace = [], $locale = null ) {
		$lower_key = mb_strtolower( $key );
		$exists = parent::getFromJson( $lower_key, $replace, $locale );

		if($exists !== $lower_key):
			return $exists;
		else:
			return $key;
		endif;
	}
}