<?php

	namespace HairPointener\Data
	{
		require_once(dirname(__FILE__).'/IGoal.php');

		interface IGoalObserver
		{
			public function notifyTagChange(IGoal $goal);
			public function notifyDescriptionChange(IGoal $goal);
			public function notifyWeightChange(IGoal $goal);
		}
	}

?>
