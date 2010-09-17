<?php
include_once('common.php');
header("Content-type: application/javascript");
/** set no cache in IE */
header("Cache-Control: no-cache");
$webim_jsonp = is_remote();
$webim_path = urlname();
$setting = json_encode(setting());
$user->show = 'unavailable';
?>
var _IMC = {
production_name: "discuzX",
version: '<?php echo $_IMC['version']; ?>',
path: '<?php echo $webim_path; ?>',
user: '<?php echo json_encode($user); ?>',
setting: '<?php echo $setting; ?>',
disable_chatlink: '<?php echo $_IMC['disable_chatlink'] ? "1" : "" ?>',
enable_shortcut: '<?php echo $_IMC['enable_shortcut'] ? "1" : "" ?>',
theme: '<?php echo $_IMC['theme']; ?>',
local: '<?php echo $_IMC['local']; ?>',
jsonp: '<?php echo $webim_jsonp ? "1" : "" ?>',
min: window.location.href.indexOf("webim_debug") != -1 ? "" : ".min"
};
_IMC.script = window.webim ? '' : ('<link href="' + _IMC.path + 'static/webim.' + _IMC.production_name + _IMC.min + '.css?' + _IMC.version + '" media="all" type="text/css" rel="stylesheet"/><link href="' + _IMC.path + 'static/themes/' + _IMC.theme + '/jquery.ui.theme.css?' + _IMC.version + '" media="all" type="text/css" rel="stylesheet"/><script src="' + _IMC.path + 'static/webim.' + _IMC.production_name + _IMC.min + '.js?' + _IMC.version + '" type="text/javascript"></script><script src="' + _IMC.path + 'static/i18n/webim-' + _IMC.local + '.js?' + _IMC.version + '" type="text/javascript"></script>');
_IMC.script += '<script src="' + _IMC.path + 'webim.js?' + _IMC.version + '" type="text/javascript"></script>';
document.write( _IMC.script );
