<?php
/**
 * EnterPrise Framework
 * 框架函数库（单字母方法）
 * @version 0.1.2
 * @author Bokjan Chan
 **/
/**
 *快速实例化
 *@param string $class 类名
 */
function I($class){
	return new $class();
}
/**
 *返回系统配置值
 *@param string $key 配置数组键名(可选)
 */
$_config=require(EP_PATH.'Conf/conf.php');
C($_config);
unset($_config);
function C($key){
	static $ep_config = array();
	//读入配置
	if(is_array($key)){
		return $ep_config=$key;
	}
	if (isset($key)) {
			if(isset($ep_config[$key])){
			return $ep_config[$key];
		}
		else{
			return NULL;
		}
	} else {
		return $ep_config;
	}
}
/**
 *返回一个URL
 *@param string $string URL信息
 */
function U($string){
	$sepe=C('URL_SEPE');
	if($sepe==NULL){
		$sepe='/';
	}
	$string=explode('?', $string);
	$class=explode('/', $string[0]);
	$param=explode('&', $string[1]);
	$url=$class[0].$sepe.$class[1].$sepe;
	$i=1;
	$j=count($param);
	foreach ($param as $element) {
		$element=explode('=', $element);
		$url.=$element[0].$sepe.$element[1];
		if($i<$j){
			$url.=$sepe;
		}
		$i++;
	}
	if(C('URL_MODE')==NULL||C('URL_MODE')==0){
		$url='./index.php/'.$url;
	}
	else{
		$url='./'.$url;
	}
	$url.=C('URL_SUFFIX');
	return $url;
}
/**
 *数据库模型实例化
 *@param string $table 数据表名
 */
function M($table=''){
	if($table==''){
		return;
	}
	$ep_obj=new epdb($table);
	return $ep_obj;
}
?>