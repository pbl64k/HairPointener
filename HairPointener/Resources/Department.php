<?php

	namespace HairPointener\Resources
	{
		require_once(dirname(__FILE__).'/AbstractHumanResource.php');

		class Department extends AbstractHumanResource
		{
			private $deptName = NULL;

			static public function make($tag, $deptName = '')
			{
				$dept = new self;

				$dept->setTag($tag)->setDeptName($deptName);

				return $dept;
			}

			public function getDeptName()
			{
				return $this->deptName;
			}

			protected function __construct()
			{
			}

			final private function setDeptName($deptName)
			{
				$this->deptName = strval($deptName);

				return $this;
			}
		}
	}

?>
