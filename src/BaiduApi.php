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

	function __construct ($appid, $api_key, $api_secret, $runtime_path) {
		$this->appid      = $appid;
		$this->api_key    = $api_key;
		$this->api_secret = $api_secret;

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

		$data = $this->http($url, '', "POST");

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

	protected function http($url, $params = '', $method = 'GET', $header = array(), $agent = array()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		if (strtoupper($method) == 'POST' && !empty($params)) {
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		if (strtoupper($method) == 'GET' && $params) {
			$query_str = http_build_query($params);
			$url       = $url . '?' . $query_str;
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		if (!empty($agent)) {
			curl_setopt($ch, CURLOPT_PROXY, $agent['ip']); //代理服务器地址
			curl_setopt($ch, CURLOPT_PROXYPORT, $agent['port']); //代理服务器端口
			//http代理认证帐号，username:password的格式
			if ($agent['username'] && $agent['password']) {
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $agent['username'] . ":" . $agent['password']);
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
			}
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		$response = curl_exec($ch);

		if (curl_errno($ch)) {
			return curl_error($ch);
		}
		curl_close($ch);

		return $response;
	}
}