<?php
$dr = dirname(dirname(dirname(dirname(__FILE__))));
$dr = rtrim( str_replace("\\", '/', $dr), '/' );
define("GIBBON_ROOT", $dr . '/'); 

require GIBBON_ROOT . 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml ;
/**
 Add Language Code Here
 */

$lang = 'en_GB';  //Never change this line..

$fpo = fopen(GIBBON_ROOT . 'i18n/'.$lang.'/LC_MESSAGES/gibbon.po', 'r');
if (file_exists(GIBBON_ROOT . 'src/i18n/'.$lang.'/gibbon.yml'))
	$x = Yaml::parse(file_get_contents(GIBBON_ROOT . 'src/i18n/'.$lang.'/gibbon.yml'));
else
	$x = array();
$msg = false;
while (($line = fgets($fpo)) !== false) {
	if (strpos($line, 'msgid ') === 0) {
		$msg = substr($line, 6);
		$msg = trim(trim($msg), "\"");
		if (empty($msg) || isset($x[$msg])) $msg = false ;
	}
	if ($msg !== false) {
		if (strpos($line, 'msgstr ') === 0) {
			$trans = trim(trim(substr($line, 7)), "\"");
			if (empty($trans)) $trans = $msg  ;

			$trans = trim(trim($trans), "\"");
			if (strlen($msg) > 0 && ! isset($x[$msg]))
				$x[$msg] = $trans ;
			
			$msg = false;
			unset($trans);

		}
	}
}
fclose($fpo);
file_put_contents(GIBBON_ROOT . "src/i18n/".$lang."/gibbon.yml", Yaml::dump($x));

$gb = $x ;

$lang = 'en_US';

$fpo = fopen(GIBBON_ROOT . 'i18n/'.$lang.'/LC_MESSAGES/gibbon.po', 'r');
if (file_exists(GIBBON_ROOT . 'src/i18n/'.$lang.'/gibbon.yml'))
	$x = Yaml::parse(file_get_contents(GIBBON_ROOT . 'src/i18n/'.$lang.'/gibbon.yml'));
else
	$x = array();
$msg = false;
while (($line = fgets($fpo)) !== false) {
	if (strpos($line, 'msgid ') === 0) {
		$msg = substr($line, 6);
		$msg = trim(trim($msg), "\"");
		if (empty($msg) || isset($x[$msg])) $msg = false ;
	}
	if ($msg !== false) {
		if (strpos($line, 'msgstr ') === 0) {
			$trans = trim(trim(substr($line, 7)), "\"");
			if (empty($trans)) $trans = $msg  ;

			$trans = trim(trim($trans), "\"");
			if (strlen($msg) > 0 && ! isset($x[$msg]))
				$x[$msg] = $trans ;
			
			$msg = false;
			unset($trans);

		}
	}
}
fclose($fpo);

foreach($x as $q=>$w)
{
	if (! isset($gb[$q]))
		unset($x[$q]);
}

file_put_contents(GIBBON_ROOT . "src/i18n/".$lang."/gibbon.yml", Yaml::dump($x));
