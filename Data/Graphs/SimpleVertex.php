<?php

	namespace Data\Graphs
	{
		require_once(dirname(__FILE__).'/IVertex.php');

		require_once(dirname(__FILE__).'/Exceptions/VertexNotAttached.php');
		require_once(dirname(__FILE__).'/Exceptions/VertexAlreadyAttached.php');

		class SimpleVertex implements IVertex
		{
			private $graphObservers = array();
	
			private $tag = NULL;
			private $oldTag = NULL;
	
			private $description = '';
	
			static public function make($tag)
			{
				$vertex = new self;
	
				$vertex->setTag($tag);
	
				return $vertex;
			}

			final public function attachTo(IGraph $graph)
			{
				$this->checkNonattachment();

				$this->graphObservers[] = $graph;
	
				return $this;
			}
	
			final public function detach()
			{
				$this->checkAttachment();

				$this->graphObservers = array();

				return $this;
			}

			final public function setTag($tag)
			{
				if ($this->tag !== $tag)
				{
					$oldTag = $this->oldTag;
					$this->oldTag = $this->tag;
					$this->tag = $tag;
		
					// This is going to break horribly in case there are multiple owner graphs and
					// only some of them are in an inconsistent state.
					try
					{
						foreach ($this->graphObservers as $observer)
						{
							$observer->notifyVertexTagChange($this);
						}
					}
					catch (Exceptions\VertexAlreadyExists $e)
					{
						$this->tag = $this->oldTag;
						$this->oldTag = $oldTag;

						throw $e;
					}
				}
	
				return $this;
			}
	
			final public function getTag()
			{
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
				}
	
				return $this;
			}
	
			final public function getDescription()
			{
				return $this->description;
			}

			final private function isAttached()
			{
				return ! empty($this->graphObservers);
			}

			final private function checkAttachment()
			{
				if (! $this->isAttached())
				{
					throw new Exceptions\VertexNotAttached($this->getTag());
				}
				
				return $this;
			}

			final private function checkNonattachment()
			{
				if ($this->isAttached())
				{
					throw new Exceptions\VertexAlreadyAttached($this->getTag());
				}
				
				return $this;
			}

			protected function __construct()
			{
			}
		}
	}

?>
