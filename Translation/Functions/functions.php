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
use Gibbon\core\trans ;
use Symfony\Component\Yaml\Yaml ;
use Symfony\Component\Yaml\Exception\ParseException ;

/**
 * module Functions
 * @version	19th September 2016
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
	 * @version	19th September 2016
	 * @since	16th September 2016
	 * @param	string		$code	Language Code
	 * @return	array		Matrix
	 */
	public function loadMatrix($code = 'en_GB')
	{
		$target = array();
		$this->validLanguage = false ;
		if (file_exists(GIBBON_ROOT.'src/i18n/'.$code.'/gibbon.yml'))
		{
			$target = Yaml::parse(file_get_contents(GIBBON_ROOT.'src/i18n/'.$code.'/gibbon.yml'));
			$this->validLanguage = true ;
		}
		return $target ;
	}

	/**
	 * Save Translation
	 * 
	 * @version	19th September 2016
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
			if (@file_put_contents(GIBBON_ROOT.'src/i18n/'.$code.'/gibbon.yml', Yaml::dump($matrix)))
			{
				$this->view->displayMessage('Translation was saved successfully.', 'success');
				return ;
			}
		}
		$this->view->displayMessage(array('%s Language code translation was not saved.', array($code)));
	}

	/**
	 * Generate Merge Form
	 * 
	 * @version	17th September 2016
	 * @since	17th September 2016
	 * @param	string	$code	Language Code
	 * @return	void
	 */
	public function generateMergeForm($code)
	{
		$merge = $this->loadMerge($code);
		if ($merge !== false)
		{
			$form = $this->view->getForm(null, array('q'=>'/modules/Translation/translationManageMerge.php'), true);
			
			$el = $form->addElement('h3', null, 'Merge Translation Conflicts');
			$el->note = 'These phrases already existed in the master file, but changes to the translation are proposed.  Select the change you wish to use for translation.';
			foreach ($merge as $key=>$w)
			{
				$form->startWell();
				$el = $form->addElement('h4', null, $key);
				$exist = is_array($w['existing']) ? Yaml::dump($w['existing']) : $w['existing'];
				$proposed = is_array($w['new']) ? Yaml::dump($w['new']) : $w['new'];
				$el->note = array('%s', array('<strong>'.$this->__('Original').':</strong> '.$exist.'<br /><strong>'.$this->__('Proposed').':</strong> '.$proposed));
				
				$el = $form->addElement('yesno', 'choice['.base64_encode($key).']', 'N');
				$el->nameDisplay = 'Choose Original';
				$el->description = 'The default choice is set to the proposed translation by this form.';
				
				$form->endWell();
			}
			$form->addElement('hidden', 'code', $code);
			$form->addElement('submitBtn', null);
			$form->render();
		}
	}

	/**
	 * loadMerge
	 * 
	 * @version	19th September 2016
	 * @since	17th September 2016
	 * @param	string	$code	Language Code
	 * @return	void
	 */
	public function loadMerge($code)
	{
		$merge = false ;
		if (file_exists(GIBBON_ROOT . 'src/i18n/'.$code.'/merge.yml'))
		{
			$merge = Yaml::parse(file_get_contents(GIBBON_ROOT . 'src/i18n/'.$code.'/merge.yml'));
			if (empty($merge)) $merge = false;
		}
		return $merge ;
	}
}