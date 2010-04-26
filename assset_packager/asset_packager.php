<?php
/*
Plugin Name: Asset Packager
Description: Package your js files and auto-compile them into one file using Google Closure
Version: 0.1
Author: Keith Norman
Author URI: http://keithnorm.com

Based on the Wordpress Asset Helper plugin http://wordpress.org/extend/plugins/asset-helper/
*/

define(GOOGLE_CLOSURE_COMPILER, 'http://closure-compiler.appspot.com/compile');

function javascript_tag() {
	$num_args = func_num_args();
	$files = array();
	$last = array_pop(func_get_args());
	$use_cache = is_array($last) && isset($last['cache']) && isset($last['cache']);
	
	if ($num_args === 0) return false;
	for ($i = 0; $i < $num_args; $i++) {
		$arg = func_get_arg($i);
		if ((is_string($arg)) && (!empty($arg))) {
			$files[] = $arg;
		}
	}
	$files = array_map('add_file_extension', $files);
	if($use_cache){
	  $cache_filename = $last['cache'];
	  $fs_path = dir_path_for('js'). add_file_extension($last['cache']);
	  if(!file_exists($fs_path)) {
	    $new_cache_file = fopen($fs_path, 'w'); 
	    _write_cache_file($files, $new_cache_file);
	  }
	  $files = array($cache_filename);
	}
	_write_script_tags($files);
}

function add_file_extension($file, $default = '.js') {
  if (!preg_match('|\.js$|', $file)) {
		return $file . $default;
	}
	else {
	  return $file;
	}
}

function curl_post($url, $fields) {
  foreach($fields as $key=>$value) {
    $fields_string .= $key.'='.urlencode($value).'&';
  }
  rtrim($fields_string,'&');

  $ch = curl_init();

  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch,CURLOPT_POST,count($fields));
  curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  $result = curl_exec($ch);

  curl_close($ch);
  return $result;
}

function _write_cache_file($files, $cache) {
  $defaults = array('compilation_level' => 'SIMPLE_OPTIMIZATIONS',
                    'output_format' => 'json',
                    'output_info' => 'compiled_code');
  $js = '';
  foreach($files as $file) {
    $fs_path = dir_path_for('js') . $file;
    if(file_exists($fs_path)){
      $js .= fread(fopen($fs_path, 'r'), filesize($fs_path));
    }	      
  }
  $defaults["js_code"] = $js;
  $compiled_reponse = curl_post(GOOGLE_CLOSURE_COMPILER, $defaults);
  fwrite($cache, json_decode($compiled_reponse)->compiledCode);
}

function _write_script_tags($files) {
  foreach ($files as $file) {
		if (!preg_match('|\.js$|', $file)) {
			$file = $file . '.js';
		}
		$path = get_path($file, 'js');
		print "<script src='${path}' type='text/javascript'></script>\n";
	}
}


function get_path($file, $type = 'js') {
  if (preg_match('|^https?://|', $file)) return $file;
	
	if (strpos($file, '/') === 0) {
		$fs_path  = $_SERVER['DOCUMENT_ROOT'] . $file;
		$url_path = $file . asset_timestamp($fs_path);
	}
	else {
	  $fs_path = $_SERVER['DOCUMENT_ROOT'] . dir_path_for($type) . $file;
		$url_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $fs_path) . asset_timestamp($fs_path);
	}
	return $url_path;
}

function dir_path_for($type = 'js') {
	$subdir = array('css' => '/stylesheets', 'js'  => '/javascripts', 'img' => '/images');
	if (is_wordpress()) {
		$dir_path  = TEMPLATEPATH . $subdir[$type] . '/';	
	}
  else {
		$dir_path = $subdir[$type] . '/';
	}
	return $dir_path;
}

function asset_timestamp($fs_path) {
	if (file_exists($fs_path)) {
		return '?' . filemtime($fs_path);
	}
}

function is_wordpress() {
	return ((defined('ABSPATH')) && (defined('TEMPLATEPATH')));
}


?>