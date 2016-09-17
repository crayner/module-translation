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
use Gibbon\core\helper ;
use Gibbon\core\trans ;
use Gibbon\core\mailer ;
use Module\Translation\Functions\functions ;
use Symfony\Component\Yaml\Yaml ;

if (! $this instanceof post) die();

$URL = array('q' => '/modules/Translation/translationManage.php');

if ($this->getSecurity()->isActionAccessible('/modules/Translation/translationManage.php'))
{

	$mf = new functions($this);
	
	if (empty($_POST['code']) || empty($_POST['choice']) || ! is_array($_POST['choice']))
	{
		$this->insertMessage('return.error.1');
		$this->redirect($URL);
	}
	$code = filter_var($_POST['code']);
	
	$source = $mf->loadMatrix($code);
	
	$merge = $mf->loadMerge($code);
	if ($merge === false)
	{
		$this->insertMessage('The correct merge file for translation was not found.');
		$this->redirect($URL);
	}

	foreach($_POST['choice'] as $q=>$w)
	{
		$key = base64_decode($q);
		$value = $w == 'Y' ? $merge[$key]['existing'] : $merge[$key]['new'] ;
		$source[$key] = $value;
		unset($merge[$key]);
	}
	
	if (false === file_put_contents(GIBBON_ROOT.'i18n/'.$code.'/gibbon.yml', Yaml::dump($source)))
	{
		$this->insertMessage('Changes to the translation file failed to save.');
		$this->redirect($URL);
	}
	unlink(GIBBON_ROOT.'i18n/'.$code.'/merge.yml');
	$this->insertMessage('return.success.0', 'success');
	$this->redirect($URL);
}
$this->insertMessage('return.error.0');
$this->redirect($URL);