<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.resonance.com.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

/**
 * ��ȡCOOKIEֵ
 * @param  string  $key                             ָ����ֵ
 * @return string  $_M['user']['cookie'][$key]	    ���ص�ǰ����Ա���Ա�����COOKIEֵ
 * ����get_gz_cookie('metinfo_admin_name'):���ص�ǰ����Ա���˺�
	   get_gz_cookie('metinfo_member_name'):���ص�ǰ��Ա���˺�
	   get_gz_cookie('metinfo_admin_pass'):���ص�ǰ����Ա������
	   get_gz_cookie('metinfo_member_pass'):���ص�ǰ��Ա������
 */
function get_gz_cookie($key){
	global $_M;
	if(defined('IN_ADMIN')){
		if($key == 'metinfo_admin_name' || $key == 'metinfo_member_name'){
			$val = urldecode($_M['user']['cookie'][$key]);
			$val = sqlinsert($val);
			return $val;
		}
		return $_M['user']['cookie'][$key];
	}else{
		$userclass = load::sys_class('user', 'new');
		if(!$userclass->get_login_user_info()){
			$userclass->login_by_auth($_M['form']['acc_auth'], $_M['form']['acc_key']);
		}
		$m = $userclass->get_login_user_info();
		$m['metinfo_admin_name'] = $m['username'];
		$m['metinfo_member_name'] = $m['username'];
		$m['metinfo_member_id'] = $m['id'];
		$m['metinfo_admin_id'] = $m['id'];                              
		$m['metinfo_admin_pass'] = $m['password'];
		$m['metinfo_member_pass'] = $m['password']; 
		if($key == 'metinfo_admin_name' || $key == 'metinfo_member_name'){
			$val = urldecode($m[$key]);
			$val = sqlinsert($val);
			return $val;
		}
		return $m[$key];
	}
}

/**
 * �ж�COOKIE�Ƿ񳬹�һ��Сʱ�����û�г��������$_M['user']['cookie']�е���Ϣ
 */
function gz_cooike_start(){
	global $_M;
	$_M['user']['cookie'] = array();
	$gz_webkeys = $_M['config']['gz_webkeys'];
	list($username, $password) = explode("\t",authcode($_M['form']['gz_auth'], 'DECODE', $gz_webkeys.$_COOKIE['gz_key']));
	$username=sqlinsert($username);
	$query = "SELECT * from {$_M['table']['admin_table']} WHERE admin_id = '{$username}'";
	$user = DB::get_one($query);
	$usercooike = json_decode($user['cookie']);
	if(md5($user['admin_pass']) == $password && time()-$usercooike->time<3600){
		foreach($usercooike as $key=>$val){
			$_M['user']['cookie'][$key] = $val;
		}
		if(defined('IN_ADMIN')){
			$_M['user']['admin_name'] = get_gz_cookie('metinfo_admin_name');
			$_M['user']['admin_id'] = $_M['user']['cookie']['metinfo_admin_id'];
			$privilege = background_privilege();
			$_M['user']['langok'] = $privilege['langok'];
		}
		$_M['user']['cookie']['time'] = time();
		$json = json_encode($_M['user']['cookie']);
		$query = "update {$_M['table']['admin_table']} set cookie = '{$json}' WHERE admin_id = '{$username}'";
		$user = DB::query($query);
	}
}

/**
 * ���COOKIE
 * @param  int $userid �û�ID    
 */
function gz_cooike_unset($userid){
	global $_M;
	$gz_admin_table = $_M['table']['admin_table'];
	$userid = sqlinsert($userid);
	$query = "UPDATE {$_M['table']['admin_table']} set cookie = '' WHERE admin_id='{$userid}' AND usertype = '3'";
	DB::query($query);
	gz_setcookie("gz_auth",'',time()-3600);
	gz_setcookie("gz_key",'',time()-3600);
	gz_setcookie("appsynchronous",0,time()-3600,'');
	unset($_M['user']['cookie']);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.resonance.com.cn). All rights reserved.
?>