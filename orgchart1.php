<?php

	require_once(dirname(__FILE__).'/init.php');

	require_once(dirname(__FILE__).'/HairPointener/Resources/Department.php');
	require_once(dirname(__FILE__).'/HairPointener/Resources/HumanResource.php');
	require_once(dirname(__FILE__).'/HairPointener/Resources/OrgChart.php');

	$hpChart = \HairPointener\Resources\OrgChart::make();

	$hpChart->addVertex(\HairPointener\Resources\Department::make('DEPT_DEVTEAM', 'Hair Pointener Development Team'));
	$hpChart->addVertex(\HairPointener\Resources\HumanResource::make('HR_PBL', 'pbl64k', 'The only guy on the team'));

	$hpChart->addArcByTags('HR_PBL', 'DEPT_DEVTEAM');

	print_r($hpChart);

?>
