<?php
/**
 * EnterPrise Framework
 * 框架函数库（单字母方法）
 * @version 0.1.6
 * @author Bokjan Chan
 **/
/**
 *快速实例化
 *@param string $class 类名
 *@return object 被实例化的对象
 */
function I($class){
	return new $class();
}
/**
 *项目配置相关
 *@param string $key 配置数组键名(可选) | array 配置值
 *@return mixed 相应配置
 */
function C($key){
	static $ep_config = array();
	//读入配置
	if(is_array($key)){
		return $ep_config=array_merge($ep_config,$key);
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
 *@return string URL
 */
function U($string){
	$sepe=C('URL_SEPE');
	if($sepe==NULL){
		$sepe='/';
	}
	if(strstr($string, '?')){
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
			//$url='./index.php/'.$url;
			$url=C('APP_URL').'index.php/'.$url;
		}
		else{
			$url='./'.$url;
		}
		$url.=C('URL_SUFFIX');
		return $url;
	}
	else{
		$class=explode('/', $string);
		if(C('URL_MODE')==NULL||C('URL_MODE')==0){
			//$url='./index.php/'.$url;
			$url=C('APP_URL').'index.php/'.$url;
		}
		else{
			$url='./';
		}
		$url.=$class[0].$sepe.$class[1].C('URL_SUFFIX');
		return $url;
	}
}
/**
 *数据库模型实例化
 *@param string $table 数据表名
 *@return object 数据库操作对象
 */
function M($table=''){
	if($table==''){
		return NULL;
	}
	$ep_obj=new epdb($table);
	return $ep_obj;
}
/**
 *钩子语句方法
 *@param string $key 语句键名
 *@return void
 */
function H($key){
	static $ep_hook = array();
	//读入配置
	if(is_array($key)){
		return $ep_hook=array_merge($ep_hook,$key);
	}
	if(isset($ep_hook[$key])){
		eval($ep_hook[$key]);
	}
	return;
}
?>