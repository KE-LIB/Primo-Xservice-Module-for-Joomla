<?php
	defined('_JEXEC') or die('Restricted access');
?>
<div>
<?php
	function isSelectedFacet($facetType, $facetta, $facetArray)
	{
		$localContain = false;
		
		for($i=0;$i<count($facetArray);$i++)
		{
			$selectedFacetType = $facetArray[$i][0];
			$facetType = preg_replace('#^#','facet_',$facetType);
			
			if($facetType==$selectedFacetType)
			{
				if($facetArray[$i][1]==$facetta)
				{
					$localContain=true;
				}else{
					$localContain = false;
				}
			}
		}
		$localContain=false;
		return $localContain;
	}

	$num = 0;
	$next = false;
	foreach($facets as $facet)
	{
		echo '<span class="label badge-info"> '.JText::_('PRIMOXS_FACETS_SIDEBAR_'.strtoupper($facet->text)).'</span><br>';
	
		$facetList = $facet->facetList;
		$facetListNum = count($facetList);
		$facetCategory = $facet->text;

		for($i=0;$i<$facetListNum;$i++)
		{	
			if($i<=5)
			{
				$facetCounts="";
				$facetType="";
				if($i%2==0)
				{
					$facetType = $facetList[$i];
				}
				else
				{
					$facetCounts = " (".$facetList[$i].")<br>";
				}

				if($i%2==0)
				{
					if(isSelectedFacet($facetCategory, $facetType, $facetLabel)==false)
					{
						echo "<a href=".$currentURL."?".$url.$num."_facet_".$facetCategory."=".preg_replace("#(')#",'',$facetType)."'>".preg_replace("#(')#",'',$facetType)."</a>";
					$num++;
					}else{
	
					}
				}
				else
				{
					if(isSelectedFacet($facetCategory, $facetList[$i-1], $facetLabel)==false)
					{
						echo $facetCounts;
					}
					
				}
			}
			else
			{
				$next = true;
			}
		}
			
		if($facetListNum>6)
		{
			if($next)
			{
				echo '<a style="text-decoration: none; color:#000;" data-toggle="collapse" data-target="#facet-'.$facet->id.'">'.JText::_('PRIMOXS_FACET_MORE').'</a><br>';
				echo '<div id="facet-'.$facet->id.'" class="collapse"><br>';
				
				for($i=6;$i<$facetListNum;$i++)
				{
					
				$facetCounts="";
				$facetType="";
				
				if($i%2==0)
				{
					$facetType = $facetList[$i];
				}
				else
				{
					$facetCounts = " (".$facetList[$i].")<br>";
				}

				if($i%2==0)
				{
					if(isSelectedFacet($facetCategory, $facetType, $facetLabel)==false)
					{
						echo "<a href=".$currentURL."?".$url.$num."_facet_".$facetCategory."=".preg_replace("#(')#",'',$facetType)."'>".preg_replace("#(')#",'',$facetType)."</a>";
						$num++;
					}else{
	
					}
				}
				else
				{
					if(isSelectedFacet($facetCategory, $facetList[$i-1], $facetLabel)==false)
					{
						echo $facetCounts;
					}
					
				}
				}
				echo "</div>";
			}
		}
		echo "<br>";
	}
?>	
</div>