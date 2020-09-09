<?php
/*------------------------------------------------------------------------
 * common.php
 *
 * 公共函数
 *
 * Created on 2020/9/9
 *
 * Author: 蚊子 <1423782121@qq.com>
 * 
 * Copyright (c) 2020 All rights reserved.
 * ------------------------------------------------------------------------
 */

/**
 * 请求HTTP数据
 * @param  [type] $url     完整URL地址
 * @param  string $params GET、POST参数
 * @param  string $method 提交方式GET、POST
 * @param  array $header Header参数
 */
function http($url, $params = '', $method = 'GET', $header = array(), $agent = array()) {
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