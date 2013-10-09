<?php
/**
 * Settings Stats View File
 *
 * @author Andrea Visinoni <andrea.visinoni@aegeebergamo.eu>
 * 
 * @license This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. 
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ 
 * or send a letter to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
 */

// set template file
$page->set_template('framework/templates/main.tpl');

// get user data
$user_data = $page->get_user_data();

if ($user_data['admin'] == 1)
{
	// set page title
	$page->set_title('Admin Settings');

	$page->AddToBody("<h2>"._("Statistics")."</h2>");
	
	// get db connection
	$db_conn = null;
	$page->get_db($db_conn);
	
	/*include("framework/charts/class/pData.class.php");
	include("framework/charts/class/pDraw.class.php");
	include("framework/charts/class/pPie.class.php");
	include("framework/charts/class/pImage.class.php");
	
	$tutored_data = $db_conn->stats_tutored_languages();
	
	$points_array = array();
	$legend_array = array();
	
	foreach ($tutored_data as $id => $t_data)
	{
		$points_array[] = $t_data['cnt'];
		$legend_array[] = $t_data['lang_name'];
	}
	
	// Create and populate the pData object
	$MyData = new pData();   
	$MyData->addPoints($points_array, "ScoreA");  
	$MyData->setSerieDescription("ScoreA", "Application A");
	
	 /* Define the absissa serie 
	$MyData->addPoints($legend_array, "Labels");
	$MyData->setAbscissa("Labels");
	
	/* Create the pChart object 
	$myPicture = new pImage(700,230,$MyData,TRUE);
	
	 /* Set the default font properties 
	$myPicture->setFontProperties(array("FontName"=>"framework/charts/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));
	
	/* Create the pPie object
	$PieChart = new pPie($myPicture,$MyData);

	/* Define the slice color 
	$PieChart->setSliceColor(0,array("R"=>143,"G"=>197,"B"=>0));
	$PieChart->setSliceColor(1,array("R"=>97,"G"=>77,"B"=>63));
	$PieChart->setSliceColor(2,array("R"=>97,"G"=>113,"B"=>63));
	
	/* Draw an AA pie chart 
	$PieChart->draw3DPie(340,125,array("DrawLabels"=>TRUE,"Border"=>TRUE));

	/* Enable shadow computing 
	$myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

	/* Draw a splitted pie chart 
	$PieChart->draw3DPie(560,125,array("WriteValues"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));

	/* Write the legend 
	$myPicture->setFontProperties(array("FontName"=>"framework/charts/fonts/pf_arma_five.ttf","FontSize"=>6));
	$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));
	$myPicture->drawText(120,200,"Single AA pass",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));
	$myPicture->drawText(440,200,"Extended AA pass / Splitted",array("DrawBox"=>TRUE,"BoxRounded"=>TRUE,"R"=>0,"G"=>0,"B"=>0,"Align"=>TEXT_ALIGN_TOPMIDDLE));

	/* Write the legend box 
	$myPicture->setFontProperties(array("FontName"=>"framework/charts/fonts/Silkscreen.ttf","FontSize"=>6,"R"=>255,"G"=>255,"B"=>255));
	$PieChart->drawPieLegend(600,8,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

	/* Render the picture (choose the best way) 
	$myPicture->stroke();*/
	
	$page->AddToBody("<img src=\"framework/view/admin/img_tutored_lang.php\" />");
}
?>
