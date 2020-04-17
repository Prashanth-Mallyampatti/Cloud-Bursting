<?php
/*
  Licensed to the Apache Software Foundation (ASF) under one or more
  contributor license agreements.  See the NOTICE file distributed with
  this work for additional information regarding copyright ownership.
  The ASF licenses this file to You under the Apache License, Version 2.0
  (the "License"); you may not use this file except in compliance with
  the License.  You may obtain a copy of the License at

      http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
*/

# ASF VCL v2.5.1
$VCLversion = '2.5.1';

require_once(".ht-inc/conf.php");

if(SSLOFFLOAD == 0) {
	if(! isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") {
		header("Location: " . BASEURL . "/");
		exit;
	}
}

$user = array();
$mode = '';
$oldmode = '';
$submitErr = '';
$submitErrMsg = '';
$remoteIP = '';
$authed = '';
$semid = '';
$semislocked = '';
unset($GLOBALS['php_errormsg']);
$cache['nodes'] = array();
$cache['unityids'] = array();
$cache['nodeprivs']['resources'] = array();
$docreaders = array();
$shibauthed = 0;
$locale = '';

require_once(".ht-inc/states.php");

require_once('.ht-inc/errors.php');

require_once('.ht-inc/utils.php');

maintenanceCheck();

dbConnect();

setVCLLocale();

initGlobals();

$modes = array_keys($actions['mode']);
//var_dump($modes);//die;
$args = array_keys($actions['args']);
$hasArg = 0;
//echo $mode;die;
if(in_array($mode, $modes)) {
	$actionFunction = $actions['mode'][$mode]; //echo $mode."  ".$actionFunction."YOOOO";
	if(in_array($mode, $args)) {
		$hasArg = 1;
		$arg = $actions['args'][$mode];
	}
}
else {
	$actionFunction = "main";
}

checkAccess();

sendHeaders();

if($_GET["mode"]=="myAction"){
	return;
}

if($_GET["mode"]=="AWStrigger"){
	$file = "/var/www/html/vcl-2.5.1/cloud_bursting/user_data/session.txt";
	$file2 = "/var/www/html/vcl-2.5.1/cloud_bursting/user_data/user_file.json";
	$text = file_get_contents($file);
	$string = file_get_contents($file2);
	$jobj = json_decode($string, true);
	$rcount = $jobj[$text]['requests'];
  #echo "{}";
#return;
	if($rcount >= 2) {
		shell_exec('bash /var/www/html/vcl-2.5.1/cloud_bursting/aws_trigger.sh');
    echo "1"; return;
    #exit;
	}

	echo "0"; return;
}

printHTMLHeader();

if(checkUserHasPerm('View Debug Information')) {
	set_error_handler("errorHandler");
}


//var_dump($actionFunction);die;
if($hasArg) {
	if(function_exists($actionFunction))
		$actionFunction($arg);
	else {
		$obj = getContinuationVar('obj');
		if(! is_null($obj) && method_exists($obj, $actionFunction))
			$obj->$actionFunction($arg);
		else
			main();
	}
}
else {
	if(function_exists($actionFunction))
		$actionFunction();
	else {
		$obj = getContinuationVar('obj');
		if(! is_null($obj) && method_exists($obj, $actionFunction))
			$obj->$actionFunction();
		else
			main();
	}
}
printHTMLFooter();

cleanSemaphore();

dbDisconnect();
?>
