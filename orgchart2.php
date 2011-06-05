<?php

	require_once(dirname(__FILE__).'/init.php');

	require_once(dirname(__FILE__).'/HairPointener/Resources/Department.php');
	require_once(dirname(__FILE__).'/HairPointener/Resources/HumanResource.php');
	require_once(dirname(__FILE__).'/HairPointener/Resources/OrgChart.php');

	$hpChart = \HairPointener\Resources\OrgChart::make();

	$hpChart->addVertex(\HairPointener\Resources\HumanResource::make('HR_RB', 'R.B.', 'CTO'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_IK', 'I.K.', 'Deputy CTO'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_VP', 'V.P.', 'System Administrator'));
	$hpChart->addChildVertex('HR_VP', \HairPointener\Resources\HumanResource::make('HR_VK', 'V.K.', 'Helpdesk Team Lead'));
	$hpChart->addChildVertex('HR_VK', \HairPointener\Resources\HumanResource::make('HR_IK_HD', 'I.K.', 'Helpdesk Staff Member'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_AL', 'A.L.', 'Team Lead, VoIP'));
	$hpChart->addChildVertex('HR_AL', \HairPointener\Resources\HumanResource::make('HR_VB', 'V.B.', 'Software Engineer'));
	$hpChart->addChildVertex('HR_AL', \HairPointener\Resources\HumanResource::make('HR_AX', 'A.X.', 'Jr. Software Engineer'));
	$hpChart->addChildVertex('HR_AL', \HairPointener\Resources\HumanResource::make('HR_SG', 'S.G.', 'QA Engineer'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_ML', 'M.L.', 'Advertisement & Promotion Manager, TRL'));
	$hpChart->addChildVertex('HR_ML', \HairPointener\Resources\HumanResource::make('HR_AP_AD', 'A.P.', 'Advertisement & Promotion Analyst'));
	$hpChart->addChildVertex('HR_ML', \HairPointener\Resources\HumanResource::make('HR_EL', 'E.L.', 'Advertisement & Promotion Analyst'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_EB', 'E.B.', 'Advertisement & Promotion Analyst'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_EM', 'E.M.', 'Project Manager, YAT'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_PL', 'P.L.', 'Software Development Manager'));
	$hpChart->addChildVertex('HR_PL', \HairPointener\Resources\HumanResource::make('HR_AP', 'A.P.', 'Team Lead, TB'));
	$hpChart->addChildVertex('HR_AP', \HairPointener\Resources\HumanResource::make('HR_MB', 'M.B.', 'Software Engineer'));
	$hpChart->addChildVertex('HR_PL', \HairPointener\Resources\HumanResource::make('HR_AR', 'A.R.', 'Team Lead, OJO/APL'));
	$hpChart->addChildVertex('HR_AR', \HairPointener\Resources\HumanResource::make('HR_LK', 'L.K.', 'Jr. Software Engineer'));
	$hpChart->addChildVertex('HR_PL', \HairPointener\Resources\HumanResource::make('HR_YK', 'Y.K.', 'Team Lead, TF'));
	$hpChart->addChildVertex('HR_YK', \HairPointener\Resources\HumanResource::make('HR_VT', 'V.T.', 'Software Engineer'));
	$hpChart->addChildVertex('HR_YK', \HairPointener\Resources\HumanResource::make('HR_YS', 'Y.S.', 'Jr. Software Engineer'));

	//print_r($hpChart);

	$maxDepths = $hpChart->getAllTags()->mfmap(function($tag) use($hpChart) { return array('tag' => $tag, 'maxDepth' => $hpChart->getMaxDepthByTag($tag)); })->pierceMonad();

	$depthGroups = array();

	foreach ($maxDepths as $hr)
	{
		if (! array_key_exists($hr['maxDepth'], $depthGroups))
		{
			$depthGroups[$hr['maxDepth']] = array();
		}

		$depthGroups[$hr['maxDepth']][] = $hr['tag'];
	}

	print_r($depthGroups);

?>
