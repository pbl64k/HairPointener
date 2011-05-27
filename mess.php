<?php

	final class Perspective
	{
	}

	interface IGoal
	{
		public function setDescription($description);
		public function getDescription();
		public function setWeight($weight);
		public function getWeight();
		public function getExternalWeight();
		public function getTotalWeight();
	}

	abstract class AbstractGoal implements IGoal
	{
	}

?>
