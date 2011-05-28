<?php

	require_once(dirname(__FILE__).'/init.php');

	require_once(dirname(__FILE__).'/HairPointener/Data/Perspective.php');
	require_once(dirname(__FILE__).'/HairPointener/Data/SimpleGoal.php');

	$p = \HairPointener\Data\Perspective::make();

	$p->addGoal(\HairPointener\Data\SimpleGoal::make('TF'));
	$p->addGoal(\HairPointener\Data\SimpleGoal::make('TFA'));
	$p->addGoal(\HairPointener\Data\SimpleGoal::make('TFB'));
	$p->addGoal(\HairPointener\Data\SimpleGoal::make('TFB1'));
	$p->addGoal(\HairPointener\Data\SimpleGoal::make('TFB2'));
	$p->addGoal(\HairPointener\Data\SimpleGoal::make('TB'));
	$p->addGoal(\HairPointener\Data\SimpleGoal::make('TBA'));
	$p->addGoal(\HairPointener\Data\SimpleGoal::make('TBB'));
	$p->addRequirementByTags('TF', 'TFA');
	$p->addRequirementByTags('TF', 'TFB');
	$p->addRequirementByTags('TFB', 'TFB1');
	$p->addRequirementByTags('TFB', 'TFB2');
	$p->addRequirementByTags('TB', 'TBA');
	$p->addRequirementByTags('TB', 'TBB');
	//$p->addRequirementByTags('TBB', 'TB');

	$p->getGoalByTag('TF')->setWeight(1);
	$p->getGoalByTag('TFA')->setWeight(11);
	$p->getGoalByTag('TFB')->setWeight(1);
	$p->getGoalByTag('TBB')->setWeight(3);
	$p->getGoalByTag('TF')->setWeight(0);

	foreach(array('TF', 'TFA', 'TFB', 'TFB1', 'TFB2', 'TB', 'TBA', 'TBB') as $t)
	{
		$g = $p->getGoalByTag($t);

		print($g->getTag().': '.$g->getTotalWeight()."\n");
	}

?>
