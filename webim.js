//custom
(function(webim){
	var path = _IMC.path;
	//var menu = webim.JSON.decode(_IMC.menu);
	webim.extend(webim.setting.defaults.data, webim.JSON.decode( _IMC.setting ));
	var webim = window.webim;
	webim.defaults.urls = {
		online:path + "online.php",
		offline:path + "offline.php",
		message:path + "message.php",
		presence:path + "presence.php",
		refresh:path + "refresh.php",
		status:path + "status.php"
	};
	webim.setting.defaults.url = path + "setting.php";
	webim.history.defaults.urls = {
		load: path + "history.php",
		clear: path + "clear_history.php"
	};
	webim.room.defaults.urls = {
		member: path + "members.php",
		join: path + "join.php",
		leave: path + "leave.php"
	};
	webim.buddy.defaults.url = path + "buddies.php";
	webim.notification.defaults.url = path + "notifications.php";

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

	im.user( webim.JSON.decode( _IMC.user ) );
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
