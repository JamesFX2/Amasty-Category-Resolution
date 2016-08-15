<html>
<head><link rel="stylesheet" type="text/css" href="style.css" media="screen">



<script type="text/javascript" src="https://code.jquery.com/jquery-3.1.0.min.js"   integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
<script type="text/javascript" src="js.js"></script>
</head><body>

<?php 

$string = file_get_contents("data.json");
$json_a = json_decode($string, true);

echo "<ul id=\"container\">";
foreach($json_a as $key => $item)
{
	if(count($item['facets'])>0)
	{
		parse_str(str_replace(":","=",str_replace(" ","&",$item['facet_url'])),$temp);
		if(!array_key_exists("cat1",$temp))
		{
			$temp['cat1'] = "";
		}
		if(!array_key_exists("cat2",$temp))
		{
			$temp['cat2'] = "";
		}
		if(!array_key_exists("cat3",$temp))
		{
			$temp['cat3'] = "";
		}
		
		
		echo "<li data-href=\"".$key."\" cat1=\"".$temp['cat1']."\"	cat2=\"".$temp['cat2']."\"	cat3=\"".$temp['cat3']."\"\"><a href=\"".$key."\">".$key."</a><ul>";
		foreach($item['facets'] as $type => $facets)
		{
			if($type !== 'Category' && $type !== 'Price')
			{
				echo "<li><span data-tag=\"".$type."\">".$type."</span><ul>";
				foreach($facets as $id => $example)
				{
					
					echo "<li>".$example."</li>";
					
					
				}
				echo "</ul></li>";
			}
		}
		echo "</ul></li>";
	}
	
}
echo "</ul>";

?>

<div id="ph"><textarea></textarea></div>

<h3>Output</h3>

</body></html>