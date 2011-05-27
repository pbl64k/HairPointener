<?php

	error_reporting(E_ALL | E_STRICT);

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

	interface IGoalObserver
	{
		public function notifyTagChange(IGoal $goal);
		public function notifyDescriptionChange(IGoal $goal);
		public function notifyWeightChange(IGoal $goal);
	}

	abstract class AbstractGoal implements IGoal
	{
		private $observers = array();

		private $tag = NULL;
		private $oldTag = NULL;

		private $description = '';

		private $weight = 0;
		private $externalWeights = array();

		final public function registerGoalObserver(IGoalObserver $observer)
		{
			$this->observers[] = $observer;

			return $this;
		}

		final public function setTag($tag)
		{
			assert(is_string($tag));

			if ($this->tag !== $tag)
			{
				$this->oldTag = $this->tag;
				$this->tag = $tag;
	
				foreach ($this->observers as $observer)
				{
					$observer->notifyTagChange($this);
				}
			}

			return $this;
		}

		final public function getTag()
		{
			assert(is_string($this->tag));

			return $this->tag;
		}

		final public function getOldTag()
		{
			return $this->oldTag;
		}

		final public function setDescription($description)
		{
			if ($this->description !== $description)
			{
				$this->description = strval($description);
	
				foreach ($this->observers as $observer)
				{
					$observer->notifyDescriptionChange($this);
				}
			}

			return $this;
		}

		final public function getDescription()
		{
			return $this->description;
		}

		final public function setWeight($weight)
		{
			assert(is_numeric($weight));

			if ($this->weight !== $weight)
			{
				$this->weight = $weight;
	
				foreach ($this->observers as $observer)
				{
					$observer->notifyWeightChange($this);
				}
			}

			return $this;
		}

		final public function getWeight()
		{
			return $this->weight;
		}

		final public function addExternalWeight($tag, $weight)
		{
			assert(is_string($tag));
			assert(is_numeric($weight));

			$this->externalWeights[$tag] = $weight;

			foreach ($this->observers as $observer)
			{
				$observer->notifyWeightChange($this);
			}

			return $this;
		}

		final public function removeExternalWeight($tag)
		{
			assert(is_string($tag));

			if (array_key_exists($tag, $this->externalWeights))
			{
				unset($this->externalWeights[$tag]);
	
				foreach ($this->observers as $observer)
				{
					$observer->notifyWeightChange($this);
				}
			}

			return $this;
		}

		final public function getExternalWeight()
		{
			$externalWeight = 0;

			foreach ($this->externalWeights as $weight)
			{
				$externalWeight += $weight;
			}

			return $externalWeight;
		}

		final public function getTotalWeight()
		{
			return $this->getWeight() + $this->getExternalWeight();
		}

		protected function __construct()
		{
		}
	}

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

	final class Perspective implements IGoalObserver
	{
		private $goals = array();

		private $reqs = array();
		private $sqer = array();

		final static public function make()
		{
			return new self;
		}

		final public function notifyTagChange(IGoal $goal)
		{
			assert($this->existsGoalByTag($goal->getOldTag()));

			$this->removeGoalByTag($goal->getOldTag());

			assert(! $this->existsGoalByTag($goal->getTag()));

			$this->goals[$goal->getTag()] = $goal;

			return $this;
		}

		final public function notifyDescriptionChange(IGoal $goal)
		{
			assert($this->existsGoalByTag($goal->getTag()));

			return $this;
		}

		final public function notifyWeightChange(IGoal $goal)
		{
			$goalTag = $goal->getTag();

			if (array_key_exists($goalTag, $this->reqs))
			{
				foreach ($this->reqs[$goalTag] as $req)
				{
					$this->getGoalByTag($req)->removeExternalWeight($goalTag)->addExternalWeight($goalTag, $goal->getTotalWeight());
				}
			}

			return $this;
		}

		final public function addGoal(IGoal $goal)
		{
			assert(! $this->existsGoalByTag($goal->getTag()));

			$this->goals[$goal->getTag()] = $goal;

			$goal->registerGoalObserver($this);

			return $this;
		}

		final public function existsGoalByTag($tag)
		{
			assert(is_string($tag));

			return array_key_exists($tag, $this->goals);
		}

		final public function getGoalByTag($tag)
		{
			assert(is_string($tag));
			assert($this->existsGoalByTag($tag));

			return $this->goals[$tag];
		}

		final public function removeGoalByTag($tag)
		{
			assert(is_string($tag));
			assert($this->existsGoalByTag($tag));

			unset($this->goals[$tag]);

			if (array_key_exists($tag, $this->reqs))
			{
				foreach ($this->reqs[$tag] as $depTag)
				{
					$this->sqer[$depTag] = array_filter($this->sqer[$depTag], function($t) use ($tag) { return $t === $t; } );
				}

				unset($this->reqs[$tag]);
			}

			if (array_key_exists($tag, $this->sqer))
			{
				foreach ($this->sqer[$tag] as $goalTag)
				{
					$this->reqs[$goalTag] = array_filter($this->reqs[$goalTag], function($t) use ($tag) { return $t === $t; } );
				}

				unset($this->sqer[$tag]);
			}

			return $this;
		}

		final public function doesRequireByTags($goalTag, $depTag)
		{
			assert(is_string($goalTag));
			assert(is_string($depTag));
			
			if (array_key_exists($goalTag, $this->reqs))
			{
				foreach ($this->reqs[$goalTag] as $req)
				{
					if ($req === $depTag)
					{
						return TRUE;
					}

					if ($this->doesRequireByTags($req, $depTag))
					{
						return TRUE;
					}
				}
			}

			return FALSE;
		}

		final public function addRequirementByTags($goalTag, $depTag)
		{
			assert(is_string($goalTag));
			assert(is_string($depTag));

			assert(! $this->doesRequireByTags($depTag, $goalTag));

			if (! array_key_exists($goalTag, $this->reqs))
			{
				$this->reqs[$goalTag] = array();
			}

			assert(! in_array($depTag, $this->reqs[$goalTag]));

			$this->reqs[$goalTag][] = $depTag;

			if (! array_key_exists($depTag, $this->sqer))
			{
				$this->sqer[$depTag] = array();
			}

			assert(! in_array($goalTag, $this->sqer[$depTag]));

			$this->sqer[$depTag][] = $goalTag;

			$this->notifyWeightChange($this->getGoalByTag($goalTag));

			return $this;
		}

		final private function __construct()
		{
		}
	}

	$p = Perspective::make();

	$p->addGoal(SimpleGoal::make('TF'));
	$p->addGoal(SimpleGoal::make('TFA'));
	$p->addGoal(SimpleGoal::make('TFB'));
	$p->addGoal(SimpleGoal::make('TFB1'));
	$p->addGoal(SimpleGoal::make('TFB2'));
	$p->addGoal(SimpleGoal::make('TB'));
	$p->addGoal(SimpleGoal::make('TBA'));
	$p->addGoal(SimpleGoal::make('TBB'));
	$p->addRequirementByTags('TF', 'TFA');
	$p->addRequirementByTags('TF', 'TFB');
	$p->addRequirementByTags('TFB', 'TFB1');
	$p->addRequirementByTags('TFB', 'TFB2');
	$p->addRequirementByTags('TB', 'TBA');
	$p->addRequirementByTags('TB', 'TBB');

	$p->getGoalByTag('TF')->setWeight(1);
	$p->getGoalByTag('TFA')->setWeight(11);
	$p->getGoalByTag('TFB')->setWeight(1);
	$p->getGoalByTag('TBB')->setWeight(3);
	$p->getGoalByTag('TF')->setWeight(0);

	foreach(array('TF', 'TFA', 'TFB', 'TFB1', 'TFB2', 'TB', 'TBA', 'TBB') as $t)
	{
		$g = $p->getGoalByTag($t);

		print($g->getTag().': '.$g->getTotalWeight()."\n");
	}

?>
