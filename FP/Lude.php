<?php

	namespace FP
	{
		final class f
		{
			static private $id = NULL;
			static private $compose = NULL;

			final static public function ap($f)
			{
				$args = func_get_args();

				array_shift($args);

				return call_user_func_array($f, $args);
			}

			final static public function id()
			{
				if (is_null(self::$id))
				{
					self::$id = function($x) { return $x; };
				}

				return self::$id;
			}

			final static public function compose()
			{
				if (is_null(self::$compose))
				{
					self::$compose = function($f)
							{
								return function($g) use($f)
								{
									return function($x) use($f, $g)
									{
										return $f($g($x));
									};
								};
							};
				}

				return self::$compose;
			}

			final private function __construct()
			{
			}
		}
	}

?>
