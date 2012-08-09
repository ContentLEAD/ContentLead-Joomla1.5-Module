<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');
jimport('joomla.filesystem.file');

// Uninstalls s system plugin named plg_myplugin
$db->setQuery('SELECT `id` FROM #__plugins WHERE `element` = "braftonpseudocron" AND `folder` = "system"');
$id = $db->loadResult();
if($id)
{
	$installer = new JInstaller;
	$result = $installer->uninstall('plugin',$id,1);
	$status->plugins[] = array('name'=>'plg_srp','group'=>'system', 'result'=>$result);
}
?>