<?php

	namespace HairPointener\Graphs
	{
		require_once(dirname(__FILE__).'/IVertex.php');

		interface IArc
		{
			public function getSourceVertex();
			public function getTargetVertex();
		}
	}

?>
