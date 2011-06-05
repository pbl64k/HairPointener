<?php

	namespace Control\Monads\tests
	{
		require_once('PHPUnit/Framework/TestCase.php');

		require_once(dirname(__FILE__).'/../IMonad.php');
		require_once(dirname(__FILE__).'/../ArrayMonad.php');

		class ArrayMonadTest extends \PHPUnit_Framework_TestCase
		{
			/**
			 * @dataProvider provideArray
			 */
			public function testCreateAndJuggle(array $array)
			{
				$monad = \Control\Monads\ArrayMonad::makeFromArray($array);

				$this->assertEquals($array, $monad->pierceMonad());

				$this->assertEquals(array($monad->pierceMonad()), $monad->mreturn($monad->pierceMonad())->pierceMonad());

				$this->assertEquals(array_map(function($x) { return 2 * $x; }, $array), $monad->mfmap(function($y) { return $y + $y; })->pierceMonad());

				$dup = array();

				foreach ($array as $elt)
				{
					$dup[] = $elt;
					$dup[] = $elt;
				}

				$this->assertEquals($dup, $monad->mbind(function ($x) { return \Control\Monads\ArrayMonad::makeFromArray(array($x, $x)); })->pierceMonad());

				return $monad;
			}

			public function provideArray()
			{
				return array(
						array(array()),
						array(array(0,)),
						array(array(1,)),
						array(array(1, 2, 3,)),
						array(array(1, 1, 2, 3, 5, 8, 13, 21,)),
						);
			}
		}
	}

?>
