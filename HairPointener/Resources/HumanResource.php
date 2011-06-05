<?php

	namespace HairPointener\Resources
	{
		require_once(dirname(__FILE__).'/AbstractHumanResource.php');

		// Send more paramedics!
		class HumanResource extends AbstractHumanResource
		{
			private $name = NULL;
			private $position = NULL;

			static public function make($tag, $name = '', $position = '')
			{
				$hr = new self;

				$hr->setTag($tag)->setName($name)->setPosition($position);

				return $hr;
			}

			public function getName()
			{
				return $this->name;
			}

			public function getPosition()
			{
				return $this->position;
			}

			protected function __construct()
			{
			}

			final private function setName($name)
			{
				$this->name = strval($name);

				return $this;
			}

			final private function setPosition($position)
			{
				$this->position = strval($position);

				return $this;
			}
		}
	}

?>
