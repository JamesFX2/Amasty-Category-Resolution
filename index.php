<?php

include "data.php";
$output = array();
$data = array();


function findCodes($data,$stringstart,$stringend)
{
	$count = 0;
	$holder = array();
	while(strpos($data,$stringstart,$count)!==false)
	{
		$start = strpos($data,$stringstart,$count) + strlen($stringstart);
		$end = strpos($data,$stringend,$start);
		$holder[] = trim(str_replace("'","",substr($data,$start,($end-$start))));
		$count = $end;
	}
	return $holder;
}


function getWebPage($url)
{
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	$text = curl_exec($ch);
	return $text;
}

foreach($category as $index => $url)
{
	$text = getWebPage($url);
	$facet_url = findCodes($text,"var facet_url='","';");
	if(count($facet_url)>0)
	{
		parse_str(explode("?",$facet_url[0])[1],$temp);
		$facet_url = urldecode($temp['af']);
	}
	else $facet_url = "";

	$document = new DOMDocument();
	
	if($text)
	{
		
		
		libxml_use_internal_errors(true);
		$document->loadHTML($text);
		libxml_clear_errors();
		


		$xpath = new DOMXpath($document);
		$result = $xpath->query('//dt');
		$facets = array();
		if($result->length > 0) 
		{
			foreach($result as $element)
			{
				$output[] = trim($element->nodeValue);
			}
			if(count($output)>0){
				foreach($output as $key => $item)
				{
					$result = $xpath->query('((//dd)['.($key+1).']/ol/li/a)');
					if($result->length > 0)
					{
						foreach($result as $element)
						{
							$facets[$item][] = trim($element->nodeValue);
						}
					}
				}
			}
		}
		$data[$url]['facets'] = $facets;
		$data[$url]['facet_url'] = $facet_url;
		unset($facets);
		unset($document);
		unset($result);
		unset($output);
	}
}

$fp = fopen("data.json", 'w');
fwrite($fp, json_encode($data));
fclose($fp);

echo "done";

?>