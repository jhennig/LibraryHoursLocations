<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// pull from main library website header
$uhead = file_get_contents('http://www.library.ubc.ca/home/includefiles/header.html');

//repair unescaped &'s in links
$uhead = preg_replace('~&([a-z0-9_]*)=~ims','&amp;\1=',$uhead);

//fix invalid html (value attribute with image type?)
$uhead = str_replace('value="Search"','',$uhead);

//believe it or not, the template gives us an unclosed <head>
if(!strpos($uhead,'</head>')){
    $uhead=str_replace('<!-- BEGIN: UBC CLF HEADER -->','</head><body><!-- BEGIN: UBC CLF HEADER -->',$uhead);
}

//library's jQuery is too fusty
$uhead=str_replace('http://www.library.ubc.ca/_ubc_clf/js/jquery-min-latest.js','http://ltk.library.ubc.ca/js/jquery.js',$uhead);

//need this id to win specificity wars with the UBC CLF
$uhead=str_replace('<body>','<body id="hours_locations">',$uhead);

$ga='
<script type="text/javascript">
    /* Google */
    var _gaq = _gaq || [];
    _gaq.push(["_setAccount", "UA-231366-5"]);
    _gaq.push(["_setDomainName", ".ubc.ca"]);
    _gaq.push(["_trackPageview"]);
    (function() {
        var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
        ga.src = ("https: " == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
        var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
';
$insert = '<link rel="stylesheet" type="text/css" href="http://ltk.library.ubc.ca/css/jquery-ui.css"/>';
$insert .= $this->Html->css('cake.ubc');
$insert .= $ga;
$insert .= '<script type="text/javascript" src="http://ltk.library.ubc.ca/js/jquery-ui.min.js"></script>';
$insert .= $scripts_for_layout;

$uhead = str_replace('</head>',$insert.'</head>', $uhead);

//Google stuff, not wanted
$uhead = preg_replace('~<script[^>]*jsapi[^>]*></script>~','',$uhead);

$page_title = 'Hours and Locations Admin: UBC Library';
if(isset($title_for_layout) && !empty($title_for_layout)){
    $page_title = $title_for_layout . ': ' . $page_title;
}

$uhead='<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="UBC Library hours and locations administration"/>
<title>'.$page_title.'</title>
'.$uhead;
if(!isset($noecho)){
    echo $uhead;
}

?>
	<div id="cake_container" class="grid">
		<div class="twelve columns nest">
			<div class="first five columns">
			<header id="hours-header" class="first twelve columns">
				<?php echo $this->Html->link(__('',true),array('controller'=>'hour_groupings','action'=>'index'),array('title'=>'Hours and Locations')); ?>
			</header>
			</div>
			<div class="last seven columns text-right" id="navlinks">
					<div style="display:inline">
						<?php 
							if($reference_hours) {
							    $class='';
								if($title_for_layout == "Dashboard") { $class = 'current_page'; }
								echo $this->Html->link(__('Dashboard',true),array('controller'=>'hour_groupings','action'=>'index'),array('class'=>$class)); 
							}
						?>
					</div>
					<div style="display:inline;">&nbsp;</div>
					<?php if($_SERVER['REMOTE_USER'] == 'hours'  || $_SERVER['REMOTE_USER'] == 'hours_admin') {  ?>
					<div style="display:inline">
						<?php 
							    $class='';
								echo $this->Html->link(__('User Guide (.pdf)',true),array('controller'=>'hour_locations','action'=>'download','hours_admin_user_guide.pdf'),array('class'=>$class)); 
						?>
					</div>
					<div style="display:inline;">&nbsp;</div>
					<?php } ?>
					<div style="display:inline">
					<?php 
						    $class='';
							if($title_for_layout == "Print Hours") { $class = 'current_page'; }
							echo $this->Html->link(__('Print Hours',true),array('controller'=>'hour_print','action'=>'index'),array('class'=>$class)); 
						?>
					</div>
					<div style="display:inline;">&nbsp;</div>
					<div style="display:inline">
					<?php 
						    $class='';
							if($title_for_layout == "Hours Widget") { $class = 'current_page'; }
							echo $this->Html->link(__('Hours Widget',true),array('controller'=>'hour_locations','action'=>'widget'),array('class'=>$class)); 
						?>
					</div>
				</div>
		</div>
		<?php 
			if(isset($location_name) && !empty($location_name)) {
		?>				
		<div>			
				<div class="twelve columns row" style="font-style:italic;">
					<?php echo "Admin tool for <strong>" . $location_name . "</strong>"; ?>
				</div>
		</div>
		<?php 
			} 
		?>
		
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $content_for_layout; ?>

		</div>
        </div> <!--container    --><?php
// include library footer

$ufoot = file_get_contents('http://www.library.ubc.ca/home/includefiles/footer.html');
$patterns = array(
    '~<!---*~', // not xml
    '~---*>~',
    '~<!-- Google Analytics.*?<!-- End UBC~mis' // just adds errors; reinserted below
);
$replacements = array(
    '<!--',
    '-->',
    '<!-- End UBC'
);
$ufoot = preg_replace($patterns, $replacements, $ufoot);
$ufoot = preg_replace('~<!-- Ask Us Tab-->.*?<\/script>.*?<\/script>~ms', '', $ufoot);

if (!empty($_SERVER['HTTPS'])) {
    $ufoot = str_replace('src="http:', 'src="/fr.php?http:', $ufoot);
}
echo '
    '.$ufoot;
echo $this->element('sql_dump'); ?>
</body>
</html>