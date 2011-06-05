<?php

	namespace Control\Monads
	{
		class ArrayMonad extends AbstractMonad implements IMonad
		{
			private $array;

			static public function makeFromArray(array $m)
			{
				return new self($m);
			}

			public function pierceMonad()
			{
				return $this->array;
			}

			public function mreturn($x)
			{
				return self::makeFromArray(array($x));
			}

			public function mjoin()
			{
				return self::makeFromArray(call_user_func_array('array_merge', $this->array));
			}

			public function mfmap($f)
			{
				return self::makeFromArray(array_map(function($x) { return $f($x); }, $this->array));
			}

			private function __construct(array $m)
			{
				$this->array = $m;
			}
		}
	}

?>
