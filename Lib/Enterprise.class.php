<?php
/**
 * EnterPrise Framework
 * 框架运行静态类
 * @author Bokjan Chan
 * @version 0.1.3
 */
class Enterprise{
	static function run(){
		$path=explode('index.php', $_SERVER['REQUEST_URI']);
		if(!isset($path[1])||$path[1]==''){
			$method=C('DEFAULT_METHOD');
			if($method==NULL){
				$ep_prog=new IndexAction();
				$ep_prog->index();
				define('ACTION','IndexAction');
				define('METHOD','Index');
			}
			else{
				$method=explode('/', $method);
				$action=$method[0].'Action';
				$method=$method[1];
				define('ACTION',$action);
				define('METHOD',$method);
				$ep_prog=new $action();
				$ep_prog->$method();
			}
		}
		else{
			self::dispatcher();
		}
	}
	static function dispatcher(){
		$path=$_SERVER['REQUEST_URI'];
		if(C('URL_SEPE')!=NULL){
			$seperator=C('URL_SEPE');
		}
		else{
			$seperator='/';
		}
		$path=str_replace($_SERVER['SCRIPT_NAME'].'/', '', $path);
		$path=str_replace('index.php/','',$path);
		$path=str_replace(C('URL_SUFFIX'),'',$path);
		if (strstr($path, $seperator)) {
			$path=explode($seperator, $path);
			$action=$path[0].'Action';
			$method=$path[1];
			unset($path[0],$path[1]);
			$key=array();
			$value=array();
			foreach ($path as $k => $v) {
				if ($k%2==0) {
					$k++;
					$_GET[$v]=$path[$k];
				}
			}
		} else {
			$action=$path.'Action';
			$method='index';
		}
		define('ACTION',$action);
		define('METHOD',$method);
		/**
		 *$action 调用的控制器名
		 *$method 调用的对应方法名
		 *pathinfo中相关GET参数直接并入$_GET变量
		 *注：若?后的GET参数与pathinfo中有重复，则以pathinfo为准
		 */
		if (method_exists($action,$method)) {
			$ep_prog=new $action();
			$ep_prog->$method();
		} else {
			$message='方法\''.$action.'::'.$method.'()\'不存在！';
			$ep_prog=new EpException();
			$ep_prog->output($message);
			exit;
		}
	}
}
class EpException extends Action{
	function output($message){
		$this->set('message',$message);
		$this->display('ep_exception.tpl');
		exit;
	}
}
?>