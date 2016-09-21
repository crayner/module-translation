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

use Gibbon\core\view;
use Gibbon\core\trans;
use Gibbon\core\helper;

if (! $this instanceof view) die();


if ($this->getSecurity()->isActionAccessible()) {
	//Proceed!
	$trail = $this->initiateTrail();
	$trail->trailEnd = 'Merge Translation File';
	$trail->render($this);
		
	
	$this->h2('Manage Translation');
	
	$this->render('default.flash');
	
	$form = $this->getForm(null, array('q'=>"/modules/Translation/translationMergeProcess.php"), true, 'TheFOrm', true);
	
	$el = $form->addElement('select', 'code', '');
	$el->nameDisplay = 'Language';
	$el->description = 'Merge this language';
	$el->setPleaseSelect();
	$lang = $this->config->getLanguages();
	foreach($lang as $q=>$w)
		$el->addOption($this->__($w), $q);	
	
	$el = $form->addElement('file', 'file');
	$el->setFile('Illegal File Type!', $within = 'yml');
	$el->setRequired();
	$el->nameDisplay = 'File to merge';

	$form->addElement('submitBtn', null, 'Merge');
	
	$form->render();

// Now check merge.
}