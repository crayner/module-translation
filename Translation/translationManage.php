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

use Gibbon\core\view ;
use Gibbon\core\helper ;
use Gibbon\core\trans ;
use Gibbon\core\mailer ;
use Module\Translation\Functions\functions ;
use Symfony\Component\Yaml\Yaml ;

if (! $this instanceof view) die();

if ($this->getSecurity()->isActionAccessible()) {
	//Proceed!
	$trail = $this->initiateTrail();
	$trail->trailEnd = 'Manage Translation';
	$trail->render($this);
		
	
	$this->h2('Manage Translation');
	
	$this->render('default.flash');
	
	$mf = new functions($this);
	
	$form = $this->getForm(null, array('q' => '/modules/Translation/translationManage.php'), false);
	
	if (isset($_POST['btn_save'])) $mf->saveTranslation();
	
	$code = isset($_POST['code']) ? $_POST['code'] : 'en_GB' ;
	
	$el = $form->addElement('select', 'code', $code);
	$el->nameDisplay = 'Target Language';
	$lang = $this->config->getLanguages();
	foreach($lang as $q=>$w)
		$el->addOption(trans::__($w), $q);	
	$el->onChangeSubmit();
	
	$editAll = isset($_POST['editAll']) ? $_POST['editAll'] : 'N' ;
	
	$el = $form->addElement('yesno', 'editAll', $editAll );
	$el->nameDisplay = 'Edit All Strings';
	$el->description = 'Access all strings in the system, or limit to missing strings.';
	if ($code == 'en_GB') $el->setDisabled();

	$source = $mf->loadMatrix();

	$target = $mf->loadMatrix($code);
	
	if (isset($_POST['btn_search']))
	{
		unset($_POST['translation'], $_POST['source']);
	}
	
	$search = isset($_POST['search']) ? $_POST['search'] : '' ;
	$el = $form->addElement('text', 'search', $search );
	$el->nameDisplay = 'Search';
	$el->description = 'Match any part of the source or target.';


	if (! empty($search))
	{
		foreach($target as $q=>$w)
		{
			$w = is_array($w) ? json_encode($w) : $w ;
			if (false === mb_strpos(strtolower($q), strtolower($search)) && false === mb_strpos(strtolower($w), strtolower($search)))
			{
				unset($target[$q]);	
			}
		}
	}
	if (! ($code == 'en_GB' || $editAll == 'Y')) 
	{
		foreach($source as $q=>$w)
			if (isset($target[$q]))
				unset($target[$q]);
	}
	
	$src = isset($_POST['source']) ? base64_decode($_POST['source']) : '' ;
	
	$el = $form->addElement('select', 'source', base64_encode($src));
	$el->nameDisplay = 'Edit this string';
	$e = 0;
	$el->addOption('');
	foreach($target as $q=>$w)
		if (++$e < 250)
			$el->addOption($q, base64_encode($q));
		else
			break ;
	$el->onChangeSubmit();
	

	$x = isset($target[$src]) ? $target[$src] : $src ;
	$x = is_array($x) ? Yaml::dump($x) : $x ;
	$x = isset($_POST['translation']) && isset($_POST['btn_save']) ? $_POST['translation'] : $x ;
	$el = $form->addELement('textArea', 'translation', $x );
	$el->nameDisplay = 'Enter translation';
	$el->description = array('Alter the translation for this source.%sThe translation can be a string or an array formatted in YAML format. YAML format details can be found at %s', array('<br />', '<br /><a href="http://www.yaml.org/spec/1.2/spec.html" target="_blank">http://www.yaml.org/spec/1.2/spec.html</a>'));
	
	$el = $form->addElement('hidden', 'task', '');
	$el = $form->addElement('buttons', null);
	$btn = $el->addButton('btn_search', 'Search', 'info');
	$btn->additional = '';
	$btn = $el->addButton('btn_save', 'Save', 'success');
	$btn->additional = '';

	$form->render();

	$form = $this->getForm(null, array('q' => '/modules/Translation/translationManage_email.php'), true);
	$form->addElement('h3', null, 'Email Translation File');
	
	$el = $form->addElement('email', 'sendTo', 'support@gibbonedu.org');
	$el->nameDisplay = 'Send to';
	$el->description = 'Send the translation file to this email address';
	$el->setRequired();
	
	$form->addElement('hidden', 'code', $code);
	
	$form->addElement('submitBtn', null, 'eMail');
	
	$form->render();
}
