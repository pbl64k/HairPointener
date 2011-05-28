<?php

	namespace HairPointener\Data
	{
		require_once(dirname(__FILE__).'/IGoal.php');
		require_once(dirname(__FILE__).'/IGoalObserver.php');

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
	}

?>
