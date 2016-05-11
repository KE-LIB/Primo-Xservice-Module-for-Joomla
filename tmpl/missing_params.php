<?php
	defined('_JEXEC') or die('Restricted access');

	$lang = JFactory::getLanguage();
	$lang->load('mod_primoxs');
?>
<label for="warn"><b><?php echo JText::_('PRIMOXS_MISSING_PARAMS'); ?></b></label>
<?php
			foreach($mParams as $param)
		{
			echo preg_replace("#[:]*#", '', JText::_('PRIMOXS_'.strtoupper($param).'_LABEL'))."<br>";
		}
?>	


	
	