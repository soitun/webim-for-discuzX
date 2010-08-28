<?php
$path = dirname(__FILE__);
$files = scandir($path);
$themes = array();
foreach ($files as $k => $v){
	$t_path = $path.DIRECTORY_SEPARATOR.$v;
	if(is_dir($t_path) && is_file($t_path.DIRECTORY_SEPARATOR."jquery.ui.theme.css")){
		array_push($themes, $v);
	}
}
echo "var themes = \"".implode(",", $themes)."\"";
?>
