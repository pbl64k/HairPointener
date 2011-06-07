<?php

	require_once(dirname(__FILE__).'/init.php');

	require_once(dirname(__FILE__).'/HairPointener/Resources/Department.php');
	require_once(dirname(__FILE__).'/HairPointener/Resources/HumanResource.php');
	require_once(dirname(__FILE__).'/HairPointener/Resources/OrgChart.php');

	require_once(dirname(__FILE__).'/View/Svg/SimpleRectRenderer.php');
	require_once(dirname(__FILE__).'/View/Svg/SimpleAcyclicDigraphTreeView.php');

	$hpChart = \HairPointener\Resources\OrgChart::make();

	$hpChart->addVertex(\HairPointener\Resources\HumanResource::make('HR_RB', 'R.B.', 'CTO'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_IK', 'I.K.', 'Infrastructure Analyst'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_VP', 'V.P.', 'System Administrator'));
	$hpChart->addChildVertex('HR_VP', \HairPointener\Resources\HumanResource::make('HR_XAE', '???', 'Automation Engineer'));
	$hpChart->addChildVertex('HR_VP', \HairPointener\Resources\HumanResource::make('HR_VK', 'V.K.', 'Support Team Lead'));
	$hpChart->addChildVertex('HR_VK', \HairPointener\Resources\HumanResource::make('HR_IK_HD', 'I.K.', 'Support Engineer'));
	$hpChart->addChildVertex('HR_VK', \HairPointener\Resources\HumanResource::make('HR_XHDSM2', '???', 'Support Engineer'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_AL', 'A.L.', 'Team Lead, VoIP'));
	$hpChart->addChildVertex('HR_AL', \HairPointener\Resources\HumanResource::make('HR_VB', 'V.B.', 'Software Engineer'));
	$hpChart->addChildVertex('HR_AL', \HairPointener\Resources\HumanResource::make('HR_AX', 'A.X.', 'UI Engineer'));
	$hpChart->addChildVertex('HR_AL', \HairPointener\Resources\HumanResource::make('HR_SG', 'S.G.', 'QA Analyst'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_ML', 'M.L.', 'Promotion Manager, TRL'));
	$hpChart->addChildVertex('HR_ML', \HairPointener\Resources\HumanResource::make('HR_AP_AD', 'A.P.', 'Promotion Analyst'));
	$hpChart->addChildVertex('HR_ML', \HairPointener\Resources\HumanResource::make('HR_EL', 'E.L.', 'Promotion Analyst, OJO'));
	$hpChart->addChildVertex('HR_ML', \HairPointener\Resources\HumanResource::make('HR_EB', 'E.B.', 'Promotion Analyst, YAT'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_VP_BA', 'V.P.', 'Business Analyst, TRL'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_EM', 'E.M.', 'Project Manager, YAT'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_PL', 'P.L.', 'Software Development Manager, TRL'));
	$hpChart->addChildVertex('HR_PL', \HairPointener\Resources\HumanResource::make('HR_AP', 'A.P.', 'Team Lead, TB'));
	$hpChart->addChildVertex('HR_AP', \HairPointener\Resources\HumanResource::make('HR_MB', 'M.B.', 'Software Engineer'));
	$hpChart->addChildVertex('HR_AP', \HairPointener\Resources\HumanResource::make('HR_XTBJSE', '???', 'Jr. Software Engineer'));
	$hpChart->addChildVertex('HR_AP', \HairPointener\Resources\HumanResource::make('HR_XTBUIE', '???', 'UI Engineer'));
	$hpChart->addChildVertex('HR_PL', \HairPointener\Resources\HumanResource::make('HR_AR', 'A.R.', 'Team Lead, OJO/APL'));
	$hpChart->addChildVertex('HR_AR', \HairPointener\Resources\HumanResource::make('HR_XOJOSE', '???', 'Software Engineer'));
	$hpChart->addChildVertex('HR_AR', \HairPointener\Resources\HumanResource::make('HR_LK', 'L.K.', 'Jr. Software Engineer'));
	$hpChart->addChildVertex('HR_AR', \HairPointener\Resources\HumanResource::make('HR_DP', 'D.P.', 'Layout Specialist'));
	$hpChart->addChildVertex('HR_PL', \HairPointener\Resources\HumanResource::make('HR_YK', 'Y.K.', 'Team Lead, TF'));
	$hpChart->addChildVertex('HR_YK', \HairPointener\Resources\HumanResource::make('HR_VT', 'V.T.', 'Software Engineer'));
	$hpChart->addChildVertex('HR_YK', \HairPointener\Resources\HumanResource::make('HR_YS', 'Y.S.', 'Jr. Software Engineer'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_SX', 'S.X.', 'Project Manager, CZ'));
	$hpChart->addChildVertex('HR_SX', \HairPointener\Resources\HumanResource::make('HR_XCBTL', '???', 'Team Lead, CB'));
	$hpChart->addChildVertex('HR_XCBTL', \HairPointener\Resources\HumanResource::make('HR_KU', 'K.U.', 'Jr. Software Engineer'));
	$hpChart->addChildVertex('HR_SX', \HairPointener\Resources\HumanResource::make('HR_AF', 'A.F.', 'Software Engineer, CF'));
	$hpChart->addChildVertex('HR_SX', \HairPointener\Resources\HumanResource::make('HR_JX', 'J.X.', 'Software Engineer, CG'));
	$hpChart->addChildVertex('HR_SX', \HairPointener\Resources\HumanResource::make('HR_AZ', 'A.Z.', 'QA Analyst'));
	$hpChart->addChildVertex('HR_RB', \HairPointener\Resources\HumanResource::make('HR_XDTL', 'X.X.', 'Design Team Lead'));
	$hpChart->addChildVertex('HR_XDTL', \HairPointener\Resources\HumanResource::make('HR_XD1', 'X.X.', 'Graphics Designer'));
	$hpChart->addChildVertex('HR_XDTL', \HairPointener\Resources\HumanResource::make('HR_XD2', 'X.X.', 'Graphics Designer'));
	$hpChart->addChildVertex('HR_XDTL', \HairPointener\Resources\HumanResource::make('HR_XLS', 'X.X.', 'Layout Specialist'));

	//print_r($hpChart);

	/*
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
	*/

	$view = \View\Svg\SimpleAcyclicDigraphTreeView::make($hpChart);

	$xml = $view->makeLayout()->setVertexRectRenderer(\View\Svg\SimpleRectRenderer::make(1, 0.5, 0.1))->generate()->serializeXml();

	print($xml);

?>
