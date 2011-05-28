<?php

	namespace HairPointener\Data
	{
		require_once(dirname(__FILE__).'/IGoalObserver.php');

		interface IGoal
		{
			public function registerGoalObserver(IGoalObserver $observer);
	
			public function setTag($tag);
			public function getTag();
			public function getOldTag();
	
			public function setDescription($description);
			public function getDescription();
	
			public function setWeight($weight);
			public function getWeight();
	
			public function addExternalWeight($tag, $weight);
			public function removeExternalWeight($tag);
			public function getExternalWeight();
	
			public function getTotalWeight();
		}
	}

?>
