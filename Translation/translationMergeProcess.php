<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

namespace Module\Translation ;

use Gibbon\core\post ;
use Gibbon\core\trans ;
use Gibbon\core\helper ;
use Gibbon\core\fileManager ;
use Symfony\Component\Yaml\Yaml ;

if (! $this instanceof post) die();

$URL = array('q' => '/modules/Translation/translationMerge.php');

if ($this->getSecurity()->isActionAccessible('/modules/Translation/translationManage.php')) {
	//Proceed!
	if (empty($_POST['code']) || empty($_FILES)) 
	{
		$this->insertMessage('return.error.1');
		$this->redirect($URL);
	}
	$fm = new fileManager($this);
	$merge = Yaml::parse($fm->extractFileContent('file'));
	
	$lang = Yaml::parse(file_get_contents(GIBBON_ROOT . 'i18n/' . $_POST['code'] . '/gibbon.yml'));
	$changes = array();
	foreach($merge as $q=>$w)
	{
		$e = trim($q, "'");
		$e = trim($e, '"');
		if (empty($lang[$e]))
		{
			$lang[$e] = $w ;
			unset($merge[$e]);
		} 
		elseif ($lang[$e] == $w)
		{
			unset($merge[$e]);
		}
		else
		{
			$changes[$e]['existing'] = $lang[$e];
			$changes[$e]['new'] = $w;
			unset($merge[$e]);
		}
	}
	
	file_put_contents(GIBBON_ROOT . 'i18n/' . $_POST['code'] . '/gibbon.yml', Yaml::dump($lang));
	if (! empty($changes))
		file_put_contents(GIBBON_ROOT . 'i18n/' . $_POST['code'] . '/merge.yml', Yaml::dump($changes));
}
$this->insertMessage('return.error.0');
$this->redirect($URL);