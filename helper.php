<?php
	defined('_JEXEC') or die('Restricted access');
	
	class modPrimoXSHelper
	{
		protected $host;
		
		protected $institution;
		
		protected $results;
		
		protected $bulkSize;
		
		function setBulkSize($setBulksize)
		{
			global $bulkSize;
			
			$bulkSize = $setBulksize;
		}
		
		function setPrimoSearch($setHost, $setInstitution)
		{
			global $host, $institution, $siteURL;
			
			$host = $setHost;
			$institution = $setInstitution;
		}
		
		function startPrimoSearch($query, $index, $sort, $facets)
		{
			global $results;

			$query = preg_replace("# #", "%20", $query);
			$facets = preg_replace("# #", "%20", $facets);

			$requestURL = modPrimoXSHelper::createRequestURL($query, $index, $sort, $facets);
			$results = modPrimoXSHelper::createResultsFrom($requestURL);

		}
		
		function getFacetList()
		{
			global $results;
			$resultObj = new stdClass();
			
			$TOTALHITS = '@TOTALHITS';
			$sum = $results->SEGMENTS->JAGROOT->RESULT->DOCSET->$TOTALHITS;	

			if($sum>=1)
			{
				$count = 0;
				
				$facetList = $results->SEGMENTS->JAGROOT->RESULT->FACETLIST->FACET;
				$facetType = "";
				foreach($facetList as $facet)
				{		
					$obj = new stdClass();
					$obj->id = $count;
					
					$fts = 0;
					$fta = 0;
					$fto = 0;

					foreach($facet as $fa)
					{
						$type = gettype($fa);
						if($type=="string")
						{
							///facetta típusa
							if($fts%2==0)
							{
								$facetType = $fa;
								$text = "PRIMOXS_FACETS_SIDEBAR_".strtoupper($fa);
								$obj->text = $fa;
							}
							$fts++;
						}
						elseif($type=="array")
						{
							$facets = array();
							for($i=0;$i<count($fa);$i++)
							{
								foreach($fa[$i] as $f)
								{
									if($fta%2==0)
									{			
										$facets[$fta] = preg_replace("#(')#",'',$f);	
									}
									else
									{
										$facets[$fta] = $f;	
									}
									$fta++;
								}
								$obj->facetList = $facets;						
							}
							
						}
						elseif($type=="object")
						{
							$facets = array();
							foreach($fa as $f)
							{
								if($fto%2==0)
									{
										$facets[$fto] = preg_replace("#(')#",'',$f);	
									}
									else
									{
										$facets[$fto] = $f;	
									}
									$fto++;
							}
							$obj->facetList = $facets;
						}
					}
					
					$resultObj->$count = $obj;
					$count++;
				}
			}
			
			return $resultObj;
		}
		
		function getPrimoSearchResults()
		{
			global $results;
			
			$resultObj = new stdClass();
			$count = 0;
			
			$TOTALHITS = '@TOTALHITS';
			$sum = $results->SEGMENTS->JAGROOT->RESULT->DOCSET->$TOTALHITS;	

			if($sum==1)
			{
				$PrimoNMBib = $results->SEGMENTS->JAGROOT->RESULT->DOCSET->DOC->PrimoNMBib->record->display;
				$links = $results->SEGMENTS->JAGROOT->RESULT->DOCSET->DOC->PrimoNMBib->record->links;
				$opac = $results->SEGMENTS->JAGROOT->RESULT->DOCSET->DOC->LIBRARIES->LIBRARY;
				$obj = new stdClass();
				$obj->id = $count;
				$obj->type = $PrimoNMBib->type;
				$obj->title = $PrimoNMBib->title;
				$obj->creator = (!empty($PrimoNMBib->creator)?$PrimoNMBib->creator:"");
				$obj->contributor = (!empty($PrimoNMBib->contributor)?$PrimoNMBib->contributor:"");
				$obj->publisher = (!empty($PrimoNMBib->publisher)?$PrimoNMBib->publisher:"");
				$obj->creationdate = (!empty($PrimoNMBib->creationdate)?$PrimoNMBib->creationdate:"");
				$obj->format = (!empty($PrimoNMBib->format)?$PrimoNMBib->format:"");
				$obj->linktotoc = (!empty($links->linktotoc)?$links->linktotoc:"");
				$obj->opac = (!empty($opac->url)?$opac->url:"");
				$obj->availlibrary = (!empty($PrimoNMBib->availlibrary)?$PrimoNMBib->availlibrary:"");
				$obj->subject = (!empty($PrimoNMBib->subject)?$PrimoNMBib->subject:"");
				$obj->language = (!empty($PrimoNMBib->language)?$PrimoNMBib->language:"");
				$obj->totalhits = $sum;
				$sourcerecordid = $results->SEGMENTS->JAGROOT->RESULT->DOCSET->DOC->PrimoNMBib->record->control->sourcerecordid;
				$obj->sourcerecordid = (!empty($sourcerecordid)?$sourcerecordid:"");
				$resultObj->$count = $obj;
			}
			else if($sum>1)
			{
				$records = $results->SEGMENTS->JAGROOT->RESULT->DOCSET->DOC;
				foreach($records as $record)
				{		
					
					$PrimoNMBib = $record->PrimoNMBib->record->display;
					$sourcerecordid = $record->PrimoNMBib->record->control->sourcerecordid;
					$links = $record->PrimoNMBib->record->links;
					
					$opac = $record->LIBRARIES->LIBRARY;

					$obj = new stdClass();
					$obj->id = $count;
					$obj->type = $PrimoNMBib->type;
					$obj->title = $PrimoNMBib->title;
					$obj->creator = (!empty($PrimoNMBib->creator)?$PrimoNMBib->creator:"");
					$obj->contributor = (!empty($PrimoNMBib->contributor)?$PrimoNMBib->contributor:"");
					$obj->publisher = (!empty($PrimoNMBib->publisher)?$PrimoNMBib->publisher:"");
					$obj->creationdate = (!empty($PrimoNMBib->creationdate)?$PrimoNMBib->creationdate:"");
					$obj->format = (!empty($PrimoNMBib->format)?$PrimoNMBib->format:"");
					$obj->linktotoc = (!empty($links->linktotoc)?$links->linktotoc:"");
					$obj->opac = (!empty($opac->url)?$opac->url:"");
					$obj->availlibrary = (!empty($PrimoNMBib->availlibrary)?$PrimoNMBib->availlibrary:"");
					$obj->sourcerecordid = (!empty($sourcerecordid)?$sourcerecordid:"");
					$obj->subject = (!empty($PrimoNMBib->subject)?$PrimoNMBib->subject:"");
					$obj->language = (!empty($PrimoNMBib->language)?$PrimoNMBib->language:"");
					$obj->totalhits = $sum;
					$resultObj->$count = $obj;
		
					$count++;
				}
			}
			else if($sum==0)
			{
				return false;
			}

			return $resultObj;
		}
		
		function createRequestURL($query, $index, $sort, $facets)
		{
			global $host, $institution, $bulkSize;

			$facet_query="";

			if($facets!=null)
			{
				for($i=0;$i<count($facets);$i++)
				{
					$facet_query.="&".preg_replace("#([\d]{1,}_)#","",$facets[$i]);
				}
			}
			$query .= $facet_query;
			$query = htmlspecialchars_decode($query);

			$requestURL = 'http://'.$host.'/PrimoWebServices/xservice/search/brief?institution='.$institution.'&query=any,exact,'.$query.'&bulkSize='.$bulkSize.'&indx='.$index.'&sortField='.$sort.'&lang=eng&json=true';
			
			return $requestURL;
		}
		
		function createResultsFrom($requestURL)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $requestURL);
			$result = curl_exec($ch);
			curl_close($ch);
			$obj = json_decode($result);
		
			return $obj;
		}	
	}
	
?>	