<?php

	namespace HairPointener\Data
	{
		require_once(dirname(__FILE__).'/AbstractGoal.php');

		final class SimpleGoal extends AbstractGoal
		{
			final static public function make($tag)
			{
				assert(is_string($tag));
	
				$goal = new self;
	
				$goal->setTag($tag);
	
				return $goal;
			}
		}
	}

?>
