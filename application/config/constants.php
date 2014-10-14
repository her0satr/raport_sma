<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ($_SERVER['SERVER_NAME'] == 'localhost') {
	define('IS_DEVEL',							true);
} else {
	define('IS_DEVEL',							false);
}

define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('SHA_SECRET',							'OraNgerti');

define('USER_ID_TU',							1);
define('USER_ID_SISWA',							2);

define('CLASS_LEVEL',							'class_level');
define('DISCIPLINE',							'discipline');
define('GRADE',									'grade');
define('HOMEROOM',								'homeroom');
define('STUDENT',								'student');
define('TEACHER',								'teacher');
define('USER',									'user');