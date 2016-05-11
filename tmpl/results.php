<?php
	defined('_JEXEC') or die('Restricted access');
	
	 if($enableSorting)
	 {
		$checked1="";
		$checked2="";
		$checked3="";
		$checked4="";
		$checked5="";
		
		if($sort=='stitle'){
			$checked1="checked";
			$checked2="";
			$checked3="";
			$checked4="";
			$checked5="";
		}
		if($sort=='relevance'){
			$checked2="selected";
			$checked1="";
			$checked3="";
			$checked4="";
			$checked5="";
		}
		if($sort=='popularity'){
			$checked3="selected";
			$checked1="";
			$checked2="";
			$checked4="";
			$checked5="";
		}
		if($sort=='screator'){
			$checked4="selected";
			$checked1="";
			$checked2="";
			$checked3="";
			$checked5="";
		}
		if($sort=='scdate'){
			$checked5="selected";
			$checked1="";
			$checked2="";
			$checked3="";
			$checked4="";
		}
	?>
	<br>
	<label for="sort"><?php echo JText::_('PRIMOXS_SORT_LABEL'); ?></label>
	<select name="sort" id="sort">
		<option value="stitle" <?php echo $checked1; ?>><?php echo JText::_('PRIMOXS_SEARCH_FILTER_STITLE');?></option>
		<option value="relevance" <?php echo $checked2; ?>><?php echo JText::_('PRIMOXS_SEARCH_FILTER_RELEVANCE');?></option>
		<option value="popularity" <?php echo $checked3; ?>><?php echo JText::_('PRIMOXS_SEARCH_FILTER_POPULARITY');?></option>
		<option value="screator" <?php echo $checked4; ?>><?php echo JText::_('PRIMOXS_SEARCH_FILTER_SCREATOR');?></option>
		<option value="scdate" <?php echo $checked5; ?>><?php echo JText::_('PRIMOXS_SEARCH_FILTER_SCDATE');?></option>
	</select>
	<br>
	<?php
	}
	if(count($facetLabel)>0)
	{
		echo "<br>".JText::_('PRIMOXS_FACETS_LABEL'); 
	}
	foreach($facetLabel as $array)
	{ 
		$text = "PRIMOXS_FACETS_SIDEBAR_".preg_replace("#(FACET_)#",'',strtoupper($array[0]));
		echo '<span onclick="deleteFacet(this.id);" class="label badge-info" id="'.$array[0].",".$array[1].'">'.preg_replace("#(')#",'',$array[1]).'</span> ';
	}
	?>
</form>
<div id="search-results">
	
	<table class="table">	
	<?php 
	$i=0;
	$num=$index;
	
	foreach($records as $record=>$element)
	{	
		$avlType = gettype($element->availlibrary);
		if($avlType=="string")
		{
			$array = explode("$$",$element->availlibrary);
		}
		elseif($avlType=="array")
		{
			$array = explode("$$",$element->availlibrary[1]);
		}
		?>				
		<tr><td>
		<h4 class="result-title ">
		<?php 
		echo (($rowNumber)?($num++)."." : "");
		if($titleAsLink)
		{
		?>
			<a target="_blank" title="<?php echo JText::_('PRIMOXS_SEARCH_RESULT_TITLE_URL'); ?>" href="<?php echo 	$opacURL.$element->sourcerecordid; ?>"><span class="highlight" rel=""><?php echo $element->title; ?></span></a>
			<span class="icon-link"></span>
		<?php
		}
		else
		{
			echo "<div style='color: #08c;'>".$element->title."</div>";
		}
		?>
		</h4>
		<?php 
		if(!empty($element->creator))
		{
			echo "<b>".JText::_('PRIMOXS_SEARCH_RESULT_AUTHOR')."</b>".$element->creator."<br>";
		}
		if($showItemStatus)
		{
			$avail=true;
			$unavail=true;
			
			$coll = preg_replace("#^[a-zA-Z\d]{1}#","",$array[12]);
			for($nli=0;$nli<count($noLoanCollections);$nli++)
			{
				if($coll==$noLoanCollections[$nli])
				{
					$avail=false;
				}
				
			}
			
			if($avail)
			{
				$status = preg_replace("#^[a-zA-Z\d]{1}#","",$array[5]);
				echo "<b>".JText::_('PRIMOXS_SEARCH_RESULT_STATUS')."</b><span class='label badge-".(($status=="available")?"success":"important")."'>".(($status=="available")?JText::_('PRIMOXS_SEARCH_RESULT_AVAIL'):JText::_('PRIMOXS_SEARCH_RESULT_UNAVAIL'))."</span><br>";
			}
			else
			{
				echo "<b>".JText::_('PRIMOXS_SEARCH_RESULT_STATUS')."</b><span class='label badge-info'>".JText::_('PRIMOXS_SEARCH_RESULT_INPLACE')."</span><br>";
			}
		}
	
	if($resultDetails)
	{
		if(false)
		{
		if(!empty($element->linktotoc)){
		?>
			<a  data-toggle="collapse" data-target="#content-<?php echo $i; ?>"><?php echo JText::_('PRIMOXS_SEARCH_TAB_CONTENT'); ?></a>
			<div id="content-<?php echo $i; ?>" class="collapse">
			<!--iframe src="<?php //echo preg_replace("#^[\$uU]*#","",$element->linktotoc);?>" style="width: 100%; height: 800px;" frameborder="0" scrolling="no"></iframe-->
			</div>
		<?php
		}
		}
		?>
		<a  data-toggle="collapse" data-target="#details-<?php echo $i; ?>"><?php echo JText::_('PRIMOXS_SEARCH_TAB_DETAILS'); ?></a>
		<div id="details-<?php echo $i; ?>" class="collapse">
		<?php 
		echo "<b>".JText::_('PRIMOXS_SEARCH_DETAILS_TITLE')."</b>".$element->title."<br>";
		echo "<b>".JText::_('PRIMOXS_SEARCH_DETAILS_AUTHOR')."</b>".$element->contributor."<br>";
		echo "<b>".JText::_('PRIMOXS_SEARCH_DETAILS_PUBLISH')."</b>".$element->publisher."<br>";
		echo "<b>".JText::_('PRIMOXS_SEARCH_DETAILS_DATE')."</b>".$element->creationdate."<br>";
		echo "<b>".JText::_('PRIMOXS_SEARCH_DETAILS_FORMAT')."</b>".$element->format."<br>";
		echo "<b>".JText::_('PRIMOXS_SEARCH_DETAILS_SUBJECT')."</b>".$element->subject."<br>";
		echo "<b>".JText::_('PRIMOXS_SEARCH_DETAILS_LANG')."</b>".$element->language."<br>";
		?>
		</div>
		<a data-toggle="collapse" data-target="#place-<?php echo $i; ?>"><?php echo JText::_('PRIMOXS_SEARCH_TAB_PLACE'); ?></a>
		<div id="place-<?php echo $i; ?>" class="collapse">
		<?php 
		echo "<b>".JText::_('PRIMOXS_SEARCH_PLACE_ITEM')."</b><a target='_blank' href='".$itemURL.$element->sourcerecordid."'>".JText::_('PRIMOXS_SEARCH_PLACE_ITEM_URL')."</a><span class='icon-link'> </span><br>";
		echo "<b>".JText::_('PRIMOXS_SEARCH_PLACE_COLLECTION')."</b>".preg_replace("#^[a-zA-Z\d]{1}#","",$array[3])."<br>";
		echo "<b>".JText::_('PRIMOXS_SEARCH_PLACE_SHELF')."</b>".preg_replace("#^[a-zA-Z\d]{1}#","",$array[4])."<br>";
		?>
		</div>
		</div>
		</div>
		</td></tr>
		<?php
	}
	$i++;
	$totalhits = $element->totalhits;
}

$class = "";
if($index==1)
{
	$class="disabled";
}	
?>
</table>
<div class=" pagination pagination-toolbar">
	<div class="pagination">
		<ul class="pagination-list">
		<li class="hidden-phone <?php echo $class; ?>"><a title="<?php echo JText::_('PRIMOXS_SEARCH_RESULT_FIRST'); ?>" href="<?php echo $currentURL."?".$url; ?>&amp;index=1" class="pagenav"><span class="icon-first"></span></a></li>
		<li class="hidden-phone <?php echo $class; ?>"><a title="<?php echo JText::_('PRIMOXS_SEARCH_RESULT_PREV'); ?>" href="<?php echo $currentURL."?".$url; ?>&amp;index=<?php echo (($index==1)?1:$index-$bulk); ?>" class="pagenav"><span class="icon-previous"></span></a></li>
		<?php 
		$spagin = $numberOfPagination;
		$start = 1;
		$currentPage = (($index-1)/$bulk)+1;

		if(($bulk*$numberOfPagination)<=$totalhits)
		{
			if($currentPage<$spagin)
			{
				echo '<li class="hidden-phone '.$class.'"><a title="1" href="'.$currentURL."?".$url.'&amp;index='.($start).'" class="pagenav" >1</a></li>';
			}
			
			if($currentPage>=$spagin)
			{
				$pagination = 0;
			}else{
				$pagination=$spagin;
			}
			
			for($i=2;$i<=$pagination;$i++)
			{
				if($currentPage==$i)
				{
					$class = "disabled";
				}else{
					$class = "";
				}
				echo '<li class="hidden-phone '.$class.'"><a title="'.$i.'" href="'.$currentURL."?".$url.'&amp;index='.($start+=$bulk).'" class="pagenav">'.$i.'</a></li>';
			}
		}
		$class = "";
		if($index>=($totalhits-($bulk-1)))
		{
			$class="disabled";
		}

		?>
		<li class="hidden-phone <?php echo $class; ?>"><a title="<?php echo JText::_('PRIMOXS_SEARCH_RESULT_NEXT'); ?>" href="<?php echo $currentURL."?".$url; ?>&amp;index=<?php echo ($index+$bulk); ?>" class="pagenav"><span class="icon-next"></span></a></li>
		<li>
		<a title="<?php echo JText::_('PRIMOXS_SEARCH_RESULT_LAST'); ?>" href="<?php echo $currentURL."?".$url; ?>&amp;index=<?php echo ($totalhits-($bulk-1)); ?>">
		<span class="icon-last"></span>
		</a>
		</li>
		</ul>
	</div>
	<div class="search-pages-counter">
	<?php echo JText::_('PRIMOXS_SEARCH_RESULT_TOTAL'); ?><strong><?php echo $index; ?></strong> - <strong><?php echo $index+($bulk-1); ?></strong> / <strong><?php echo $totalhits; ?></strong>
	</div>
</div>
</div>
<script>
function deleteFacet(value)
{
	var facet = value.split(',');
	var url = document.URL;
	var re = new RegExp("&"+facet[0]+"=[a-zA-Z0-9%]*(|&)","g");
	var newurl = url.replace(re,'');
	window.location = newurl;
}
</script>