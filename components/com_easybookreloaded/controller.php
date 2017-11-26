<?php
/**
 * EBR - Easybook Reloaded for Joomla! 3.x
 * License: GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Author: Viktor Vogel <admin@kubik-rubik.de>
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
defined('_JEXEC') or die('Restricted access');

class EasybookReloadedController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		parent::display();
	}

	/**
	 * This function is triggered when the user clicks on the publish link the notification mail
	 */
	public function publish_mail()
	{
		$hashrequest = JFactory::getApplication()->input->getString('hash');
		$check_hash = $this->performMail($hashrequest);
		$gbid = JFactory::getApplication()->input->getInt('gbid');

		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_COULD_NOT_CHANGE_PUBLISH_STATUS');
		$type = 'error';

		if($check_hash == true)
		{
			$model = $this->getModel('entry');

			switch($model->publish())
			{
				case -1:
					$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_COULD_NOT_CHANGE_PUBLISH_STATUS');
					$type = 'error';
					break;
				case 0:
					$msg = JText::_('COM_EASYBOOKRELOADED_ENTRY_UNPUBLISHED');
					$type = 'success';
					break;
				case 1:
					$msg = JText::_('COM_EASYBOOKRELOADED_ENTRY_PUBLISHED');
					$type = 'success';
					break;
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false), $msg, $type);
	}

	private function performMail($hashrequest)
	{
		// No empty hash value allowed
		if(empty($hashrequest))
		{
			return false;
		}

		// Prepare request string
		$hash_data = explode('-', $hashrequest);

		// The hash_data array must have 2 entries - ID of entry and the hash itself
		if(count($hash_data) != 2)
		{
			return false;
		}

		if(empty($hash_data[0]) OR !is_numeric($hash_data[0]))
		{
			return false;
		}

		// Check whether the ID is available and numeric - load the entry for the checks
		$model = $this->getModel('entry');
		$gbrow = $model->getRow($hash_data[0]);

		// Check whether the hash link is still valid
		$params = JComponentHelper::getParams('com_easybookreloaded');

		$app = JFactory::getApplication();
		$offset = $app->get('offset');

		$date_entry = JFactory::getDate($gbrow->get('gbdate'), $offset);
		$date_now = JFactory::getDate('now', $offset);

		$valid_time_emailnot = $params->get('valid_time_emailnot') * 60 * 60 * 24;

		if($date_entry->toUnix() + $valid_time_emailnot <= $date_now->toUnix())
		{
			return false;
		}

		// Create a second hash link from the same data and compare it with the transmitted hash value
		$hash = array();
		$hash['id'] = (int)$gbrow->get('id');
		$hash['gbmail'] = md5($gbrow->get('gbmail'));
		$hash['username'] = $gbrow->get('gbname');

		// Get config object for the secret word and sitename
		$config = JFactory::getConfig();
		$hash['custom_secret'] = $config->get('secret');

		$secret_word = $params->get('secret_word');

		if(!empty($secret_word))
		{
			$hash['custom_secret'] = $params->get('secret_word');
		}

		$hash = substr(base64_encode(md5(serialize($hash))), 0, 16);

		if($hash != $hash_data[1])
		{
			return false;
		}

		return true;
	}

	/**
	 * This function is triggered when the user clicks on the remove link the notification mail
	 */
	public function remove_mail()
	{
		$hashrequest = JFactory::getApplication()->input->getString('hash');
		$check_hash = $this->performMail($hashrequest);
		$gbid = JFactory::getApplication()->input->getInt('gbid');

		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_ENTRY_COULD_NOT_BE_DELETED');
		$type = 'error';

		if($check_hash == true)
		{
			$model = $this->getModel('entry');

			if($model->delete())
			{
				$msg = JText::_('COM_EASYBOOKRELOADED_ENTRY_DELETED');
				$type = 'success';
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false), $msg, $type);
	}

	/**
	 * This function is triggered when the user clicks on the comment link the notification mail
	 */
	public function comment_mail()
	{
		$input = JFactory::getApplication()->input;
		$hashrequest = $input->getString('hash');
		$check_hash = $this->performMail($hashrequest);
		$gbid = $input->getInt('gbid');

		if($check_hash == true)
		{
			$input->set('view', 'entry');
			$input->set('layout', 'commentform_mail');
			$input->set('hidemainmenu', 1);
			parent::display();

			return;
		}

		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_COULD_NOT_SAVE_COMMENT');
		$type = 'error';
		$this->setRedirect(JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false), $msg, $type);
	}

	/**
	 * This function is triggered when the user saves the comment form which was called from the notification mail
	 */
	public function savecomment_mail()
	{
		$hashrequest = JFactory::getApplication()->input->getString('hash');
		$check_hash = $this->performMail($hashrequest);
		$gbid = JFactory::getApplication()->input->getInt('gbid');

		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_COULD_NOT_SAVE_COMMENT');
		$type = 'error';

		if($check_hash == true)
		{
			$model = $this->getModel('entry');

			if($row = $model->saveComment())
			{
				// Change state of the guestbook entry
				if(isset($row['toggle_state']) AND $row['toggle_state'] == 1)
				{
					$model->publish();
				}

				$msg = JText::_('COM_EASYBOOKRELOADED_COMMENT_SAVED');

				if(isset($row['inform']) AND $row['inform'] == 1)
				{
					$data = $model->getRow($row['id']);
					$uri = JUri::getInstance();
					$mail = JFactory::getMailer();
					$params = JComponentHelper::getParams('com_easybookreloaded');
					require_once(JPATH_COMPONENT.'/helpers/route.php');

					$href = $uri->base().EasybookReloadedHelperRoute::getEasybookReloadedRoute($data->get('id'), $gbid);
					$mail->setSubject(JText::_('COM_EASYBOOKRELOADED_ADMIN_COMMENT_SUBJECT'));
					$mail->setBody(JText::sprintf('COM_EASYBOOKRELOADED_ADMIN_COMMENT_BODY', $data->get('gbname'), $uri->base(), $href));

					if($params->get('send_mail_html'))
					{
						$mail->isHtml(true);
						$mail->setBody(JText::sprintf('COM_EASYBOOKRELOADED_ADMIN_COMMENT_BODY_HTML', $data->get('gbname'), $uri->base(), $href));
					}

					$mail->addRecipient($data->get('gbmail'));
					$mail->Send();

					$msg = JText::_('COM_EASYBOOKRELOADED_COMMENT_SAVED_INFORM');
				}

				$type = 'success';
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false), $msg, $type);
	}

	/**
	 * This function is triggered when the user clicks on the edit link the notification mail
	 */
	public function edit_mail()
	{
		$input = JFactory::getApplication()->input;
		$hashrequest = $input->getString('hash');
		$check_hash = $this->performMail($hashrequest);
		$gbid = $input->getInt('gbid');

		if($check_hash == true)
		{
			$input->set('view', 'entry');
			$input->set('layout', 'form_mail');
			parent::display();

			return;
		}

		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_PLEASE_VALIDATE_YOUR_INPUTS');
		$type = 'error';
		$this->setRedirect(JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false), $msg, $type);
	}

	/**
	 * This function is triggered when the user saves the edit form which was called from the notification mail
	 */
	public function save_mail()
	{
		$hashrequest = JFactory::getApplication()->input->getString('hash');
		$check_hash = $this->performMail($hashrequest);
		$gbid = JFactory::getApplication()->input->getInt('gbid');

		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_COULD_NOT_SAVE_COMMENT');
		$type = 'error';

		if($check_hash == true)
		{
			$params = JComponentHelper::getParams('com_easybookreloaded');

			// Reset the time to avoid error in the spam check
			$session = JFactory::getSession();
			$time = $session->get('time', null, 'easybookreloaded');
			$session->set('time', $time - $params->get('type_time_sec'), 'easybookreloaded');

			$model = $this->getModel('entry');

			if($model->store())
			{
				$msg = JText::_('COM_EASYBOOKRELOADED_ENTRY_SAVED_BUT_HAS_TO_BE_APPROVED');
				$type = 'message';

				if($params->get('default_published', true))
				{
					$msg = JText::_('COM_EASYBOOKRELOADED_ENTRY_SAVED');
					$type = 'success';
				}
			}
		}

		$this->setRedirect(JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false), $msg, $type);
	}
}
