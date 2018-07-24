define(function(require, exports, module) {

	var $ = jQuery = require('jquery');
	
	var metcst=document.querySelector('meta[name="generator"]').getAttribute('data-variable'),
		DataStr=metcst.split("|"),
		gz_weburl=DataStr[0],
		lang=DataStr[1],
		classnow=parseInt(DataStr[2]),
		id=parseInt(DataStr[3]),
		gz_module=parseInt(DataStr[4]),
		gz_skin_user=DataStr[5];
		
		window.gz_weburl 		= gz_weburl;	//��ַ
		window.lang 			= lang;	 		//����
		window.classnow		    = classnow;		//��ǰ��ĿID
		window.id 				= id;			//��ǰҳ��ID
		window.gz_module 		= gz_module;	//����ģ��
		window.gz_skin_user 	= gz_skin_user;//����ģ��
	
	/*��������*/
	var StranBody = $(".StranBody");        	
	if(StranBody.length>0){
		require.async('pub/weboverall/ch/ch');
	}
	
	/*���߽��� - վ��ͳ�� */
	var url=gz_weburl+'include/interface/uidata.php?lang='+lang,h = window.location.href;
	if(h.indexOf("preview=1")!=-1)url = url + '&theme_preview=1';
	$.ajax({
		type: "POST",
		url: url,
		dataType:"json",
		success: function(msg){
			var c = msg.config;
			if(c.gz_online_type!=3){	  //���߽���
				require.async('pub/weboverall/online/online');
			}
			if(c.gz_stat==1){			  //վ��ͳ��
				var navurl=classnow==10001?'':'../';
				var	stat_d=classnow+'-'+id+'-'+lang;
				var	url = gz_weburl+'include/stat/stat.php?type=para&u='+navurl+'&d='+stat_d;
				$.getScript(url);
			}
		}
	});
	
});