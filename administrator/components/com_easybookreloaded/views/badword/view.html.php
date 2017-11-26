<?php
/**
 * EBR - Easybook Reloaded for Joomla! 3.x
 * License: GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Author: Viktor Vogel
 * Project page: https://joomla-extensions.kubik-rubik.de/ebr-easybook-reloaded
 *
 * @license GNU/GPL
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') OR die('Restricted access');

class EasybookReloadedViewBadword extends JViewLegacy
{
	protected $badword;
	protected $donation_code_message;

	function display($tpl = null)
	{
        JHtml::_('stylesheet', 'easybookreloaded.css', array('relative' => 'administrator/components/com_easybookreloaded/css/'));

		$this->badword = $this->get('Data');
		$isNew = ($this->badword->id < 1);

		$text = $isNew ? JText::_('COM_EASYBOOKRELOADED_BADWORDNEW') : JText::_('COM_EASYBOOKRELOADED_BADWORDEDIT');
		JToolbarHelper::title(JText::_('COM_EASYBOOKRELOADED_BADWORDFILTER').': <small>['.$text.']</small>', 'easybookreloaded');
		JToolbarHelper::apply('apply');
		JToolbarHelper::save();

		if($isNew)
		{
			JToolbarHelper::cancel();
		}
		else
		{
			JToolbarHelper::cancel('cancel', 'Close');
		}

		// Get donation code message
		require_once JPATH_COMPONENT.'/helpers/easybookreloaded.php';
		$this->donation_code_message = EasybookReloadedHelper::getDonationCodeMessage();

		parent::display($tpl);
	}
}
