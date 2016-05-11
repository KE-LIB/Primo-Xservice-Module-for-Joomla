<?php
	defined('_JEXEC') or die('Restricted access');

?>	
<form class="form-inline">
	<label for="query"><?php echo JText::_('PRIMOXS_SEARCH_LABEL'); ?></label>
	<input type="text" name="query"  id="query" size="30" value="<?php echo $query;?>" class="inputbox" >
	<button name="Search" type="submit" class="btn btn-primary"><span class="icon-search icon-white"></span> <?php echo JText::_('PRIMOXS_SEARCH_BUTTON');?></button>
	<br>
<?php
	if($records==false)
	{
		echo JText::_('PRIMOXS_SEARCH_NORESULTS');
	}
?>
