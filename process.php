<?php

$output = array();

$string = file_get_contents("values.json");
$json_a = json_decode($string, true);
$server_version = isset($json_a['version']) ? $json_a['version'] : 0;

if(!empty($_POST) && $_POST["data"] && $_POST["version"])
{
	$version = filter_var($_POST["version"],FILTER_SANITIZE_NUMBER_INT);
	$array = $_POST["data"];
	if($version > $server_version)
	{
		foreach($array as $key => $item)
		{
			if(strpos($key,"http://www") !== false)
			{
				foreach($item as $id => $flag)
				{
					$output["data"][$key][] = filter_var($flag,FILTER_SANITIZE_SPECIAL_CHARS);
				}		
			
			}
			
		}
		$output["version"] = $version;
		$fp = fopen("values.json", 'w');
		fwrite($fp, json_encode($output));
		fclose($fp);
		echo json_encode(array("success" => true));
	}
	else echo json_encode(array("success" => false));
		
	
}
else {
	if(!empty($_GET) && $_GET["version"] == $server_version) {
		echo json_encode(array("version" => $server_version));				
	}
	else echo $string;
}

?>