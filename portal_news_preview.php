<?php

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
$plugins->add_hook("portal_announcement", "portal_news_preview");

function portal_news_preview_info()
{
	return array(
		"name"			=> "Portal News Preview",
		"description"	=> "Cut the news in the portal after the first image adding a READ MORE button. You can choose three different way to cut the News: after the first image, after a string or after N characthers",
		"website"		=> "http://erbodega.github.io",
		"version"		=> "1.0",
		"author"		=> "bodega",
		"authorsite"	=> "http://ps4mod.net",
		"compatibility"  => "18*",
		"guid" => ""
	);
}


function portal_news_preview_install()
{
    global $db;
    require_once(MYBB_ROOT."admin/inc/functions_themes.php");

    $stylesheet = ".readmore, .readmore:visited {
	background-color: #237;
	color: #fff;
	float: left;
	padding: 10px;
	margin: 20px;
	transition: box-shadow linear 0.25s, background linear 0.25s;
}


.readmore:hover {
	background-color: #06b !important;
	box-shadow: 0px 1px 9px 2px #555;
	color: #fff;
	transition: box-shadow linear 0.2s, background linear 0.2s;
	text-decoration: none;
}

.readmore:active {
	background-color: #0066a6 !important;
	color: #fff;
	text-decoration: none;	
	outline: none;
}";
	
    $portal_news_preview_stylesheet = array(
        'sid' => NULL,
        'name' => 'readmore.css',
        'tid' => '1',
        'stylesheet' => $db->escape_string($stylesheet),
        'cachefile' => 'readmore.css',
        'lastmodified' => TIME_NOW,
    );

	cache_stylesheet(1, "readmore.css", $stylesheet);
	$sid = $db->insert_query("themestylesheets", $portal_news_preview_stylesheet);
    $db->update_query("themestylesheets", array("cachefile" => "css.php?stylesheet=".$sid), "sid = '".$sid."'", 1);
    $tids = $db->simple_select("themes", "tid");
    while($theme = $db->fetch_array($tids))
    {
        update_theme_stylesheet_list($theme['tid']);
    }
} 

function portal_news_preview_is_installed()
{
    global $db;

	$query = $db->simple_select("themes", "tid");
	while($tid = $db->fetch_field($query, "tid")) {
		$css_file = MYBB_ROOT."cache/themes/theme{$tid}/readmore.css";
		if(file_exists($css_file)) {
			return true;
		}
    }
}


function portal_news_preview_uninstall()
{
    global $db;
    require_once(MYBB_ROOT."admin/inc/functions_themes.php");

    $query = $db->simple_select("themes", "tid");
    while($tid = $db->fetch_field($query, "tid"))
    {
        $css_file = MYBB_ROOT."cache/themes/theme{$tid}/readmore.css";
		$mincss_file = MYBB_ROOT."cache/themes/theme{$tid}/readmore.min.css";
        if(file_exists($css_file)) {
            unlink($css_file);
			unlink($mincss_file);
		}
    }
    
	$db->delete_query("themestylesheets", "name = 'readmore.css'");

    require_once MYBB_ADMIN_DIR."inc/functions_themes.php";
	
    $query = $db->simple_select("themes", "tid");
    while($theme = $db->fetch_array($query))
    {
        update_theme_stylesheet_list($theme['tid']);
    } 
} 



function portal_news_preview_activate()
{
	global $db, $mybb;
	
	require_once MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('portal_announcement', '#'.preg_quote('{$message}').'#', '{$message}<br/><br/>{$announcement[\'link\']}');

	$query = $db->simple_select("settinggroups", "gid", "name='portal'");
	$gid = $db->fetch_field($query, "gid");

	$setting = array(
		'name' => 'isenabled',
		'title' => 'Portal News Preview',
		'description' => 'Activate the plugin in order to show only a part of the news adding a readmore button?',
		'optionscode' => 'onoff',
		'value' => '1',
		'disporder' => '12',
		'gid' => intval($gid)
	);
	$db->insert_query('settings',$setting);
	
	$whichway = array(
		'name' => 'whichway',
		'title' => 'How would you like to cut the news',
		'description' => 'Set how you want to cut the news on portal. By default is set to After first image (WordPress like), that is what almost every admin want',
		'optionscode' => 'radio \n 1=After the first image (WordPress like) \n 2=Before a string that can be set below \n 3=After some characthers, below you can set how many characthers show',
		'value' => '1',
		'disporder' => '13',
		'gid' => intval($gid)
	);
	$db->insert_query('settings',$whichway);


	$cutstring = array(
		'name' => 'cutstring',
		'title' => 'Cut String',
		'description' => 'If you choose to cut the news after a string, set here the string. If in the middle of a news you write exactly this string, in the portal you will see the news cutted before this string. Is suggested that you to use small symbol like * ° or _ .',
		'optionscode' => 'text',
		'value' => 'cut_me_here',
		'disporder' => '14',
		'gid' => intval($gid)
	);
	$db->insert_query('settings',$cutstring);

	
	$characters = array(
		'name' => 'characters',
		'title' => 'How many Characters?',
		'description' => 'If you choose to cut the news after some characters, set here how many characters. It may show broken bbcode if the setted up number is in the middle of a bbcode tag',
		'optionscode' => 'text',
		'value' => '20',
		'disporder' => '15',
		'gid' => intval($gid)
	);
	$db->insert_query('settings',$characters);

	
	$readmoremessage = array(
		'name' => 'readmoremessage',
		'title' => 'Read More Button Text',
		'description' => 'Set the message for the "read more" button. The &raq... present by default is simply this: ». If you prefer others special characters, please use HTML Entity or HTML Number',
		'optionscode' => 'text',
		'value' => '&raquo; READ MORE',
		'disporder' => '16',
		'gid' => intval($gid)
	);
	$db->insert_query('settings',$readmoremessage);

rebuild_settings();
}

function portal_news_preview_deactivate()
{
	global $db, $mybb;
	require_once MYBB_ROOT.'inc/adminfunctions_templates.php';
	find_replace_templatesets('portal_announcement', '#'.preg_quote('<br/><br/>{$announcement[\'link\']}').'#', '', 0);

	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='isenabled'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='whichway'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='cutstring'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='characters'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='readmoremessage'");

rebuild_settings();
}


function portal_news_preview()
{
	global $db, $mybb, $announcement;

	if($mybb->settings['isenabled'] == 1) {
		
		if($mybb->settings['whichway'] == 1) {
			$announcement['message'] = substr($announcement['message'], 0, strpos($announcement['message'], '[/img]', strpos($announcement['message'], '[img', 0)) + 6);
			$announcement['link'] = "<strong><a class=\"readmore\" target=\"_blank\" href=".$mybb->settings['bburl']."/".$announcement['threadlink'].">".$mybb->settings['readmoremessage']."</a></strong>";
		}
			
		if($mybb->settings['whichway'] == 2) {
			$announcement['message'] = substr($announcement['message'], 0, strpos($announcement['message'], $mybb->settings['cutstring'], 0 ));
			$announcement['link'] = "<strong><a class=\"readmore\" target=\"_blank\" href=".$mybb->settings['bburl']."/".$announcement['threadlink'].">".$mybb->settings['readmoremessage']."</a></strong>";
		}

		if($mybb->settings['whichway'] == 3 AND $mybb->settings['characters'] < my_strlen($announcement['message'])) {
			$announcement['message'] = substr($announcement['message'], 0, $mybb->settings['characters']);
			$announcement['link'] = "<strong><a class=\"readmore\" target=\"_blank\" href=".$mybb->settings['bburl']."/".$announcement['threadlink'].">".$mybb->settings['readmoremessage']."</a></strong>";
		}
	}
}
?>
