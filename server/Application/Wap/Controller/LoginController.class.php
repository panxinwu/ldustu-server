<?php
namespace Wap\Controller;
use Think\Controller;
header('Content-Type: text/html; charset=utf-8;');
class LoginController extends Controller {
	/*
	*登陆首页
	*登陆功能页面
	*退出
	*/
	public function login(){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$openid = $_POST['openid'];
		echo $openid;
		if(!isset($username)||!isset($password)){
			if(!isset($openid)){
				$returnJson = array(
				'error' => 1001,
				);
			}
			else{
				$user = D('user');
				$where['openid'] = $openid;
				$userResult = $user->where($where)->find();
				//dump($userResult);
				if($userResult&&$userResult!=''){
					$returnJson['error'] = 0;
				}else{
					$returnJson['error'] = 1002;
				}
			}
		}else{	
			$where = array(
			'username'=>$username,
			'password'=>$password,
			);
			
			$result = $user->where($where)->field('passwd',true)->find();
			
			cookie('id',$result['id'],3600);
			$more['login_time'] = time();
			$more['login_style'] = LoginStyle();
			$user->where($where)->data($more)->save();
			if($result&&$result!=''&&cookie('id')){
				//dump($more);
				$returnJson = array(
					'error'=>0,
					);
			}elseif(!$result||$result = ''){
				$returnJson = array(
					'error'=>1002,
					);
			}	
		}
		
		print_r(json_encode($returnJson));
	}
	public function logout(){
		cookie('id',null);
		$returnJson = array(
				'error'=>0,
				);
		print_r(json_encode($returnJson));
	}
	public function loginJudge(){ 
	   	$id = cookie('id');
    		if($id&&$id!=''){ //判断是否登陆过
    			$returnJson = array(
    				'error' =>0,
    				);
    		}else{
    			$returnJson = array(
    				'error' =>1003,
    				);
    		}
    		print_r(json_encode($returnJson));
	   }
}