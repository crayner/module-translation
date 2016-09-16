<?php
/**
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

namespace Module\Translation\Functions ;

use Gibbon\core\moduleFunctions as mFBase ;
use Gibbon\core\module as helper ;
use Symfony\Component\Yaml\Yaml ;
use Symfony\Component\Yaml\Exception\ParseException ;

/**
 * module Functions
 * @version	16th September 2016
 * @since	16th September 2016
 * @package		Module
 */
class functions extends mFBase
{
	/**
	 * @var	boolean		Valid Language
	 */
	private	$validLanguage ;

	/**
	 * Load Matrix
	 * 
	 * @version	16th September 2016
	 * @since	16th September 2016
	 * @param	string		$code	Language Code
	 * @return	array		Matrix
	 */
	public function loadMatrix($code = 'en_GB')
	{
		$target = array();
		$this->validLanguage = false ;
		if (file_exists(GIBBON_ROOT.'i18n/'.$code.'/gibbon.yml'))
		{
			$target = Yaml::parse(file_get_contents(GIBBON_ROOT.'i18n/'.$code.'/gibbon.yml'));
			$this->validLanguage = true ;
		}
		return $target ;
	}

	/**
	 * Save Translation
	 * 
	 * @version	16th September 2016
	 * @since	16th September 2016
	 * @return	void
	 */
	public function saveTranslation()
	{
		if (empty($_POST['source'])) return ;
		$code = filter_var($_POST['code']);
		$matrix = $this->loadMatrix($code);
		if ($this->validLanguage)
		{
			$key = base64_decode(filter_var($_POST['source'])) ;
			$trans = filter_var($_POST['translation']) ;
			try {
				$trans = Yaml::parse($trans);
			} catch (ParseException $e) {
				$this->view->displayMessage(array("Unable to parse the YAML string: %s", array($e->getMessage())));
				return ;
			}
			$matrix[$key] = $trans ;
			if (file_put_contents(GIBBON_ROOT.'i18n/'.$code.'/gibbon.yml', Yaml::dump($matrix)))
			{
				$this->view->displayMessage('Translation was saved successfully.', 'success');
				return ;
			}
		}
		$this->view->displayMessage('Translation was not saved.');
	}
}