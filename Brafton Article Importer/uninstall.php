<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.installer.installer');
jimport('joomla.filesystem.file');

$installer = new JInstaller;
$src = $this->parent->getPath('source');
$installer->uninstall($src.DS.'plg_braftonpseudocron');
?>