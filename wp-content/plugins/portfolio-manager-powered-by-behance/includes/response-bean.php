<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
if(!class_exists("Response_Bean")){
class Response_Bean{
	
	public static function setMsg($msg = ""){
		session_start();
		$_SESSION['eds_bpm_msg'] = $msg; 
		session_write_close();
	} 
	
	public static function getMsg(){
		session_start();
		if(isset($_SESSION['eds_bpm_msg'])){
			$bpm_msg = isset($_SESSION['eds_bpm_msg']) && !empty($_SESSION['eds_bpm_msg']) ? $_SESSION['eds_bpm_msg'] : null ;
			session_write_close();
			return $bpm_msg;
		}
		session_write_close();
		return null;
	}
	
	public static function unsetMsg(){
		session_start();
		unset($_SESSION['eds_bpm_msg']);
		session_write_close();
	}
	
	public static function setFlag($flag = true){
		session_start();
		$_SESSION['eds_bpm_flag'] = $flag; 
		session_write_close();
	} 
	
	public static function getFlag(){
		session_start();
		if(isset($_SESSION['eds_bpm_flag'])){
			$flag = isset($_SESSION['eds_bpm_flag']) && !empty($_SESSION['eds_bpm_flag']) ? $_SESSION['eds_bpm_flag'] : null ;
			session_write_close();
			return $flag;
		}	
		
		session_write_close();
		return 	null;
	}
	
	public static function unsetFlag(){
		session_start();
		unset($_SESSION['eds_bpm_flag']);
		session_write_close();
	}
}
}
