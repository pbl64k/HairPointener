<?php

	namespace HairPointener\Resources
	{
		require_once(dirname(__FILE__).'/../../Data/Graphs/AcyclicDigraph.php');

		require_once(dirname(__FILE__).'/AbstractHumanResource.php');

		class OrgChart extends \Data\Graphs\AcyclicDigraph
		{
			static public function make()
			{
				return new self;
			}

			protected function isValidHumanResource(AbstractHumanResource $hr)
			{
				return TRUE;
			}

			protected function isValidVertex(\Data\Graphs\IVertex $vertex)
			{
				return $this->isValidHumanResource($vertex);
			}
		}
	}

?>
