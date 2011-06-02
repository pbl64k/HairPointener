<?php

	namespace HairPointener\Data
	{
		require_once(dirname(__FILE__).'/IEdgedVertex.php');
		require_once(dirname(__FILE__).'/IEdgedVertexOwner.php');

		class EdgedVertex implements IEdgedVertex
		{
			private $owner = NULL;

			private $parent = array();
			private $child = array();

			private $tag = NULL;
			private $oldTag = NULL;
	
			private $description = '';
	
			final public function attachTo(IEdgedVertexOwner $owner)
			{
				$this->observers[] = $observer;
	
				return $this;
			}
	
			final public function setTag($tag)
			{
				assert($owner->isValidTag($tag));
	
				if ($this->tag !== $tag)
				{
					$this->oldTag = $this->tag;
					$this->tag = $tag;
		
					$owner->notifyTagChange($this);
				}
	
				return $this;
			}
	
			final public function getTag()
			{
				assert($owner->isValidTag($this->tag));
	
				return $this->tag;
			}
	
			final public function getOldTag()
			{
				assert(is_null($this->oldTag) || $owner->isValidTag($this->oldTag));

				return $this->oldTag;
			}
	
			final public function setDescription($description)
			{
				if ($this->description !== $description)
				{
					$this->description = strval($description);
		
					$owner->notifyDescriptionChange($this);
				}
	
				return $this;
			}
	
			final public function getDescription()
			{
				return $this->description;
			}
	
			protected function __construct()
			{
			}
		}
	}

?>
