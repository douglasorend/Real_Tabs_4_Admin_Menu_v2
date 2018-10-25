<?php
/**********************************************************************************
* Subs-ReorganizeAdmin.php - Function to reorganize Admin menu
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANsectionILITY
* or FITNESS FOR A PARTICULAR PURPOSE .
**********************************************************************************/

function RA_Reorganize(&$areas)
{
	global $txt;

	// Create a "Security" section before the "Forum" section and include "Security and Moderation" settings here:
	$new = array();
	foreach ($areas as $needle => $section)
	{
		if ($needle == 'layout')
			$new['security'] = array(
				'title' => $txt['admin_security'],
				'permission' => array('admin_forum'),
				'areas' => array(
					'securitysettings' => $areas['config']['areas']['securitysettings'],
				),
			);
		$new[$needle] = $section;
	}
	$areas = $new;
	unset($new);
	unset($areas['config']['areas']['securitysettings']);

	// Move the "httpBL", "Bad Behavior" and "Forum FireWall" settings to this section:
	if (isset($areas['config']['areas']['forumfirewall']))
		$areas['security']['areas']['forumfirewall'] = $areas['config']['areas']['forumfirewall'];
	unset($areas['config']['areas']['forumfirewall']);

	if (isset($areas['config']['areas']['badbehavior']))
		$areas['security']['areas']['badbehavior'] = $areas['config']['areas']['badbehavior'];
	unset($areas['config']['areas']['badbehavior']);

	if (isset($areas['members']['areas']['httpBL']))
		$areas['security']['areas']['httpBL'] = $areas['members']['areas']['httpBL'];
	unset($areas['members']['areas']['httpBL']);

	// Create "Modifications" section using "Modifications Settings" area:
	$areas['mods'] = array(
		'title' => $txt['admin_mods'],
		'permission' => array('admin_forum'),
		'areas' => array(
			'modsettings' => $areas['config']['areas']['modsettings'],
		),
	);
	unset($areas['config']['areas']['modsettings']);

	// Remove most modifications from "Configuration" section and put under "Modifications":
	$haystack = array('corefeatures', 'featuresettings', 'languages', 'serversettings', 'modsettings', 'current_theme', 'theme');
	foreach ($areas['config']['areas'] as $needle => $area)
	{
		if (!in_array($needle, $haystack))
		{
			$areas['mods']['areas'][$needle] = $areas['config']['areas'][$needle];
			unset($areas['config']['areas'][$needle]);
		}
	}

	// Create "Package Manager" section, removing it from "Main" section:
	$areas['packages'] = array(
		'title' => $txt['package'],
		'permission' => array('admin_forum', 'manage_permissions', 'moderate_forum', 'manage_membergroups', 'manage_bans', 'send_mail', 'edit_news', 'manage_boards', 'manage_smileys', 'manage_attachments'),
		'areas' => array(
			'packages' => $areas['forum']['areas']['packages'],
		),
	);
	unset($areas['forum']['areas']['packages']);
}

?>