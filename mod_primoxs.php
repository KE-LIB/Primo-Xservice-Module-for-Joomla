<?php
	defined('_JEXEC') or die('Restricted access');
	
	$missingParameters = false;
	$mParams = array();
	$records =  false;
	$resultDetails = false;
	$showItemStatus = false;
	$enableSorting = false;
	$titleAsLink = false;
	$bulk = 10;
	$numberOfPagination = 0;
	$rowNumber = false;
	$facetLabel = array();
	$noLoanCollections = array();
	$currentURL = JURI::current();
	$url="";
	
	/**
	 * Check missing settings parameters like host, institute, etc.
	 */
	foreach($params as $key => $value)
	{
		if($value == null && ($key == "host" || $key == "inst"))
		{
			$missingParameters = true;
			array_push($mParams,$key);
		}
	}
	
	/**
	 * get right layout
	 */
	if($missingParameters==false)
	{
		
		foreach($_REQUEST as $key => $value)
		{
			$url.= $key."=".$value."&";
		}
		
		$jinput = JFactory::getApplication()->input;
		$query = $jinput->get('query', '', '');
		$sort = $jinput->get('sort', '', '');
		$index = JRequest::getVar('index', '');
		
		$facets=null;
		$ri=0;
		foreach($_REQUEST as $key => $value)
		{
			if(preg_match("#facet_#",$key, $match))
			{
				$facetLabel[$ri][0] = $key;
				$facetLabel[$ri][1] = preg_replace("#(')#",'',$value);
				if(preg_match("#facet_creationdate#",$key, $match))
				{
					$facets[$ri] = "query=".$key.",exact,%5B".preg_replace("#(')#",'',$value)."%2BTO%2B".preg_replace("#(')#",'',$value)."%5D";
				}
				else
				{
					$facets[$ri] = "query=".$key.",exact,".preg_replace("#(')#",'',$value);
				}
				$ri++;
			}
		}

		require_once (dirname(__FILE__).'/helper.php');
		
		modPrimoXSHelper::setBulkSize($params->get('bulksize'));
		modPrimoXSHelper::setPrimoSearch($params->get('host'), $params->get('inst'));
		
		$index = ((strlen($index)==0)? 1 : $index);
		$sort = ((strlen($sort)==0)? $params->get('defsort') : $sort);
		$opacURL = $params->get('opacurl');
		$itemURL = $params->get('itemurl');
		$bulk = $params->get('bulksize');
		$numberOfPagination = $params->get('pagenum');
		$noLoanCollections = explode(",",$params->get('nlcolls'));

		if($params->get('rownum')==1)
		{
			$rowNumber=true;
		}
		if($params->get('titleurl')==1)
		{
			$titleAsLink=true;
		}
		if($params->get('details')==1)
		{
			$resultDetails=true;
		}
		if($params->get('status')==1)
		{
			$showItemStatus=true;
		}
		if($params->get('sorting')==1)
		{
			$enableSorting=true;
		}
		
		modPrimoXSHelper::startPrimoSearch($query, $index, $sort, $facets);		
		
		if($params->get('facetmode')==1)
		{					
			$records = modPrimoXSHelper::getPrimoSearchResults();
			$layout = $params->get('layout','search_form');
			require JModuleHelper::getLayoutPath('mod_primoxs', $layout);
			if($query!=null)
			{
				if($records!=false)
				{
					$layout = $params->get('layout','results');
					require JModuleHelper::getLayoutPath('mod_primoxs', $layout);	
				}

			}
		}
		elseif($params->get('facetmode')==0)
		{
			if(!empty($query))
			{
				$facets = modPrimoXSHelper::getFacetList();
				
				$layout = $params->get('layout','sidebar');
				require JModuleHelper::getLayoutPath('mod_primoxs', $layout);
			}
		}
	}
	else
	{
		$layout = $params->get('layout','missing_params');
		require JModuleHelper::getLayoutPath('mod_primoxs', $layout);
	}
?> 