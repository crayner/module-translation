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

use Gibbon\core\post;
use Gibbon\core\mailer ;
use Gibbon\core\trans ;
use Gibbon\core\helper;

if (! $this instanceof post) die();

$URL = array('q' => '/modules/Translation/translationManage.php');

if ($this->getSecurity()->isActionAccessible('/modules/Translation/translationManage.php')) {
	//Proceed!
	
	
	if (file_exists(GIBBON_ROOT . 'i18n/'.$_POST['code'].'/gibbon.yml'))
	{
		$mailer = new mailer();
		
		$mailer->IsHTML();
		$mailer->AddAttachment(GIBBON_ROOT . 'i18n/'.$_POST['code'].'/gibbon.yml');      // attachment
		
		$body = '<p>' . trans::__('Attached is a translation file to merge with the master copy.  It is for the %s language', array($_POST['code'])) . '</p>';
		$body .= '<p>' . trans::__('Gibbon Site %s', array($this->session->get('organisationName'))) . '</p>';
		
		$mailer->Subject    = trans::__("Translation File Update");
		$mailer->AltBody    = trans::__("To view the message, please use an HTML compatible email viewer!"); // optional, comment out and test
		$mailer->Body = $body;
		
		$mailer->AddEmbeddedImage(GIBBON_ROOT . $this->session->get('organisationLogo'), 'logoImg', 'logoImg.png');
		
		$mailer->addAddress($_POST['sendTo']); 
		
		if (! $mailer->send())
		{
			$this->insertMessage('The translation file was not emailed!');
			$this->redirect($URL);
		}
		$this->insertMessage('The translation file was successsfully sent.', 'success');
		$this->redirect($URL);
	}
	$this->insertMessage('The translation file was not found!');
	$this->redirect($URL);
}
$this->insertMessage('return.error.0');
$this->redirect($URL);
