<?php

	namespace HairPointener\Data
	{
		require_once(dirname(__FILE__).'/IGoal.php');
		require_once(dirname(__FILE__).'/IGoalObserver.php');

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
	}

?>
