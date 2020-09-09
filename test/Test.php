<?php
/*------------------------------------------------------------------------
 * Test.php
 * 	
 * Created on 2020/9/9
 *
 * Author: 蚊子 <1423782121@qq.com>
 * 
 * Copyright (c) 2020 All rights reserved.
 * ------------------------------------------------------------------------
 */

require __DIR__ . '/../vendor/autoload.php';

use villain\BaiduApi\BaiduApi;

$api = new BaiduApi('22593436', 'xkYKCpWwglDXiZDweah2YdwM', 'KhGeOfS4XaMm5RROb7Yi0yp5AccR1712');

$api->getAccessToken();