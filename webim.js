//custom
(function(webim){
	var path = _IMC.path;
	//var menu = webim.JSON.decode(_IMC.menu);
	webim.extend(webim.setting.defaults.data, _IMC.setting );
	var webim = window.webim;
	webim.defaults.urls = {
		online:path + "im.php?action=online",
		offline:path + "im.php?action=offline",
		message:path + "im.php?action=message",
		presence:path + "im.php?action=presence",
		refresh:path + "im.php?action=refresh",
		status:path + "im.php?action=status"
	};
	webim.setting.defaults.url = path + "im.php?action=setting";
	webim.history.defaults.urls = {
		load: path + "im.php?action=history",
		clear: path + "im.php?action=clear_history"
	};
	webim.room.defaults.urls = {
		member: path + "im.php?action=members",
		join: path + "im.php?action=join",
		leave: path + "im.php?action=leave"
	};
	webim.buddy.defaults.url = path + "im.php?action=buddies";
	webim.notification.defaults.url = path + "im.php?action=notifications";

	webim.ui.emot.init({"dir": path + "static/images/emot/default"});
	var soundUrls = {
		lib: path + "static/assets/sound.swf",
		msg: path + "static/assets/sound/msg.mp3"
	};
	var ui = new webim.ui(document.body, {
		imOptions: {
			jsonp: _IMC.jsonp
		},
		soundUrls: soundUrls
	}), im = ui.im;

	im.user( _IMC.user );
	//ui.addApp("menu", {"data": menu});
	//rm shortcut in uchome
	//ui.layout.addShortcut( menu);
	ui.addApp("buddy");
	ui.addApp("room");
	ui.addApp("notification");
	ui.addApp("setting", {"data": webim.setting.defaults.data});
	if( !_IMC.disable_chatlink )ui.addApp("chatlink", {
		space_href: [/mod=space&uid=(\d+)/i, /space\-uid\-(\d+)\.html$/i],
		space_class: /xl\sxl2\scl/,
		space_id: null,
		link_wrap: document.getElementById("ct")
	});
	ui.render();
	im.autoOnline() && im.online();
})(webim);
