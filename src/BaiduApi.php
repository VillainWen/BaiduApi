<?php
/*------------------------------------------------------------------------
 * BaiduApi.php 
 * 	
 * Description
 *
 * Created on 2020/9/9
 *
 * Author: 蚊子 <1423782121@qq.com>
 * 
 * Copyright (c) 2020 All rights reserved.
 * ------------------------------------------------------------------------
 */
namespace villain\BaiduApi;

use villain\BaiduApi\Cache;
use villain\BaiduApi\Logs;

class BaiduApi {
	/**
	 * 开发者ID
	 * @var
	 */
	protected $appid;

	/**
	 * 应用Key
	 * @var
	 */
	protected $api_key;

	/**
	 * 应用密钥
	 * @var
	 */
	protected $api_secret;

	/**
	 * 缓存路径
	 * @var string
	 */
	protected $runtime_path;

	function __construct ($appid, $api_key, $api_secret, $runtime_path='') {
		$this->appid      = $appid;
		$this->api_key    = $api_key;
		$this->api_secret = $api_secret;

		$runtime_path = $runtime_path ? $runtime_path : root_path();
		$this->runtime_path = $runtime_path . 'runtime/villain/';

		Cache::init($this->runtime_path . 'simplecache/');
	}

	public function getAccessToken () {
		$appid  = $this->api_key;
		$secret = $this->api_secret;

		if(!$appid || !$secret){
			$this->logs('未设置AppId和Secret！');
			return false;
		}

		$token = Cache::get($appid . '_token');

		if($token){
			return $token;
		}

		$url = 'https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id=' . $this->api_key . '&client_secret=' . $this->api_secret;

		$data = http($url, '', "POST");

		var_dump($data);exit;
	}

	/**
	 * [logs 日志]
	 * @param  string $content [description]
	 * @return [type]          [description]
	 */
	private function logs ($content = '') {
		$Logs = new Logs();
		$Logs->logs($content, $this->runtime_path);
	}


}