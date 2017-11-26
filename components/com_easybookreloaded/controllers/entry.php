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

class EasybookReloadedControllerEntry extends JControllerLegacy
{
	protected $input;

	public function __construct()
	{
		parent::__construct();

		$this->input = JFactory::getApplication()->input;
	}

	public function add()
	{
		$this->add_edit();
	}

	private function add_edit()
	{
		$params = JComponentHelper::getParams('com_easybookreloaded');
		$id = $this->input->getInt('cid', 0);

		if((($id == 0 AND EASYBOOK_CANADD) OR ($id != 0 AND EASYBOOK_CANEDIT)) AND !$params->get('offline'))
		{
			$this->input->set('view', 'entry');
			$this->input->set('layout', 'form');
			parent::display();

			return;
		}

		$link = JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.JFactory::getSession()->get('gbid', false, 'easybookreloaded'), false);
		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_RIGHTS');
		$type = 'message';
		$this->setRedirect($link, $msg, $type);
	}

	public function edit()
	{
		$this->add_edit();
	}

	/**
	 * Saves the entry and inform the administrator(s) or show an error message to entry creator
	 */
	public function save()
	{
		JSession::checkToken() OR jexit('Invalid Token');

		// Clean page cache if System Cache plugin is enabled
		if(JPluginHelper::isEnabled('system', 'cache'))
		{
			$this->cleanCache();
		}

		$params = JComponentHelper::getParams('com_easybookreloaded');
		$id = $this->input->getInt('id', 0);
		$gbid = JFactory::getSession()->get('gbid', false, 'easybookreloaded');

		if((($id == 0 AND EASYBOOK_CANADD) OR ($id != 0 AND EASYBOOK_CANEDIT)) AND !$params->get('offline'))
		{
			$model = $this->getModel('entry');

			// Store the entered data, create an output message and send the notification mail
			if($row = $model->store())
			{
				$msg = JText::_('COM_EASYBOOKRELOADED_ENTRY_SAVED_BUT_HAS_TO_BE_APPROVED');
				$type = 'notice';

				if($params->get('default_published', true))
				{
					$msg = JText::_('COM_EASYBOOKRELOADED_ENTRY_SAVED');
					$type = 'success';
				}

				$link = JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false);

				// Send mail if it is a new entry and the send mail option is activated
				if($id == 0 AND $params->get('send_mail', true))
				{
					// Reference needed objects and prepare the requested variables for the mail
					require_once(JPATH_COMPONENT.'/helpers/route.php');
					$mail = JFactory::getMailer();
					$uri = JUri::getInstance();
					$db = JFactory::getDbo();

					// Load all request variables - because JInput doesn't allow to load the whole data at once, a workaround
					//is used. This was easily possible with the deprecated JRequest (e.g. JRequest::get('post');)
					$temp_data = $_REQUEST;
					array_walk($temp_data, create_function('&$temp_data', '$temp_data = htmlspecialchars(strip_tags(trim($temp_data)));'));

					// Get unfiltered request variable is only with a trick with JInput possible, so direct access is used instead
					// Possible solution: list($gbtext) = ($this->_input->get('gbtext', array(0), 'array') - use the filter array
					// With JRequest one could use - JRequest::getVar('gbtext', NULL, 'post', 'none', JREQUEST_ALLOWRAW)
					// Update: Now possible with RAW filter parameter in newer Joomla! versions, due to B/C no changes here
					$temp_data['gbtext'] = htmlspecialchars($_REQUEST['gbtext'], ENT_QUOTES);

					$name = $temp_data['gbname'];
					$text = $temp_data['gbtext'];

					$ip = '0.0.0.0';

					if($params->get('enable_log', true))
					{
						require_once(JPATH_COMPONENT.'/helpers/content.php');
						$ip = EasybookReloadedHelperContent::getIpAddress();
					}

					$title = '';

					if(!empty($temp_data['gbtitle']))
					{
						$title = $temp_data['gbtitle'];
					}

					// Get config object for the secret word, sitename and email settings
					$config = JFactory::getConfig();

					$hash = array();
					$hash['id'] = (int)$row->get('id');
					$hash['gbmail'] = md5($row->get('gbmail'));
					$hash['username'] = $row->get('gbname');

					// Get the custom secret word. If no word was set, take the Joomla! secret word from the configuration
					$hash['custom_secret'] = $config->get('secret');
					$secret_word = $params->get('secret_word');

					if(!empty($secret_word))
					{
						$hash['custom_secret'] = $params->get('secret_word');
					}

					$hash = substr(base64_encode(md5(serialize($hash))), 0, 16);
					$hash_id = $row->get('id').'-'.$hash;

					$href = $uri::base().EasybookReloadedHelperRoute::getEasybookReloadedRoute($row->get('id'), $gbid);
					$hashmail_publish = $uri::base().EasybookReloadedHelperRoute::getEasybookReloadedRouteHash('publish_mail', $gbid).$hash_id;
					$hashmail_comment = $uri::base().EasybookReloadedHelperRoute::getEasybookReloadedRouteHash('comment_mail', $gbid).$hash_id;
					$hashmail_edit = $uri::base().EasybookReloadedHelperRoute::getEasybookReloadedRouteHash('edit_mail', $gbid).$hash_id;
					$hashmail_delete = $uri::base().EasybookReloadedHelperRoute::getEasybookReloadedRouteHash('remove_mail', $gbid).$hash_id;

					// Mail subject - get the name of the website and add it to the subject
					$site_name = $config->get('sitename');
					$mail->setSubject(JText::sprintf('COM_EASYBOOKRELOADED_NEW_GUESTBOOKENTRY', $site_name));
					$mail->setBody(JText::sprintf('COM_EASYBOOKRELOADED_A_NEW_GUESTBOOKENTRY_HAS_BEEN_WRITTEN', $uri::base(), $name, $title, $text, $href, $hashmail_publish, $hashmail_comment, $hashmail_edit, $hashmail_delete, $ip));

					if($params->get('send_mail_html'))
					{
						$mail->isHtml(true);
						$mail->setBody(JText::sprintf('COM_EASYBOOKRELOADED_A_NEW_GUESTBOOKENTRY_HAS_BEEN_WRITTEN_HTML', $uri::base(), $name, $title, $text, $href, $hashmail_publish, $hashmail_comment, $hashmail_edit, $hashmail_delete, $ip));
					}

					// Get mail addresses for the notification mail
					$admins = array();
					$emailfornotification_usergroup_array = $params->get('emailfornotification_usergroup', array(8));

					foreach($emailfornotification_usergroup_array as $emailfornotification_usergroup)
					{
						$query = "SELECT ".$db->quoteName('email')." FROM ".$db->quoteName('#__users')." AS A, ".$db->quoteName('#__user_usergroup_map')." AS B WHERE ".$db->quoteName('B.group_id')." = ".$db->quote($emailfornotification_usergroup)." AND ".$db->quoteName('B.user_id')." = ".$db->quoteName('A.id')." AND ".$db->quoteName('A.sendEmail')." = 1";
						$db->setQuery($query);
						$result = $db->loadRowList();

						if(!empty($result))
						{
							foreach($result as $value)
							{
								$admins[] = $value[0];
							}
						}
					}

					if($params->get('emailfornotification'))
					{
						$emailfornotification = array_map('trim', explode(',', $params->get('emailfornotification')));

						foreach($emailfornotification as $email)
						{
							$admins[] = $email;
						}
					}

					// Set recipient and reply to addresses
					$reply_to = $row->get('gbmail');

					if(empty($reply_to))
					{
						$reply_to = $config->get('mailfrom');
					}

					$mail->addRecipient($admins);
					$mail->addReplyTo($reply_to, $row->get('gbname'));
					$mail->setSender(array($config->get('mailfrom'), $config->get('fromname')));

					// Which mail type should be used? Default is PHP mail
					if($config->get('mailer') == 'sendmail')
					{
						$mail->useSendmail($config->get('sendmail'));
					}
					elseif($config->get('mailer') == 'smtp')
					{
						$mail->useSmtp($config->get('smtpauth'), $config->get('smtphost'), $config->get('smtpuser'), $config->get('smtppass'), $config->get('smtpsecure'), $config->get('smtpport'));
					}

					// Send the mail
					$mail->Send();
				}

				$this->setRedirect($link, $msg, $type);

				return;
			}

			$errors_output = array();
			$errors_array = array_keys(JFactory::getSession()->get('errors', null, 'easybookreloaded'));

			if((in_array('easycalccheck', $errors_array)) OR (in_array('easycalccheck_time', $errors_array)))
			{
				if(in_array('easycalccheck_time', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_EASYCALCCHECK_TIME');
				}
				else
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_EASYCALCCHECK');
				}
			}
			elseif(in_array('akismet', $errors_array))
			{
				$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_AKISMET');
			}
			elseif(in_array('gbid', $errors_array))
			{
				$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_GBID');
			}
			elseif(in_array('easycalccheck_question', $errors_array))
			{
				$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_SPAMCHECKQUESTION');
			}
			else
			{
				if(in_array('name', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_NAME');
				}

				if(in_array('mail', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_MAIL');
				}

				if(in_array('title', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_TITLE');
				}

				if(in_array('text', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_TEXT');
				}

				if(in_array('aim', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_AIM');
				}

				if(in_array('icq', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_ICQ');
				}

				if(in_array('yah', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_YAH');
				}

				if(in_array('skype', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_SKYPE');
				}

				if(in_array('msn', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_MSN');
				}

				if(in_array('toomanylinks', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_TOOMANYLINKS');
				}

				if(in_array('iptimelock', $errors_array))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_ERROR_TIMELOCK');
				}

				if(empty($errors_output))
				{
					$errors_output[] = JText::_('COM_EASYBOOKRELOADED_UNKNOWNERROR');
				}
			}

			$errors = implode(', ', $errors_output);

			$msg = JText::sprintf('COM_EASYBOOKRELOADED_PLEASE_VALIDATE_YOUR_INPUTS', $errors);
			$link = JRoute::_('index.php?option=com_easybookreloaded&controller=entry&task=add&retry=true', false);
			$type = 'error';

			JFactory::getSession()->clear('errors', 'easybookreloaded');
			$this->setRedirect($link, $msg, $type);

			return;
		}

		$link = JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false);
		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_RIGHTS');
		$type = 'message';
		$this->setRedirect($link, $msg, $type);
	}

	/**
	 * Cleans the cached pages of the component by the system cache plugin
	 *
	 * @deprecated Used due to B/C reasons for older Joomla! versions
	 */
	private function cleanCache()
	{
		$gbid = JFactory::getSession()->get('gbid', false, 'easybookreloaded');
		$cache = JFactory::getCache();

		$id = md5(JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false));
		$cache->remove($id, 'page');
		$id_entry = md5(JRoute::_('index.php?option=com_easybookreloaded&controller=entry&task=add', false));
		$cache->remove($id_entry, 'page');
		$id_entry_retry = md5(JRoute::_('index.php?option=com_easybookreloaded&controller=entry&task=add&retry=true', false));
		$cache->remove($id_entry_retry, 'page');

		return;
	}

	/**
	 * Calls the comment form if user has the correct permission rights
	 */
	public function comment()
	{
		if(EASYBOOK_CANEDIT)
		{
			$this->input->set('view', 'entry');
			$this->input->set('layout', 'commentform');
			$this->input->set('hidemainmenu', 1);
			parent::display();

			return;
		}

		$link = JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.JFactory::getSession()->get('gbid', false, 'easybookreloaded'), false);
		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_RIGHTS');
		$type = 'message';
		$this->setRedirect($link, $msg, $type);
	}

	/**
	 * Removes an entry from the database
	 */
	public function remove()
	{
		// Clean page cache if System Cache plugin is enabled
		if(JPluginHelper::isEnabled('system', 'cache'))
		{
			$this->cleanCache();
		}

		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_RIGHTS');
		$type = 'message';

		if(EASYBOOK_CANEDIT)
		{
			$model = $this->getModel('entry');

			if(!$model->delete())
			{
				$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_ENTRY_COULD_NOT_BE_DELETED');
				$type = 'error';
			}
			else
			{
				$msg = JText::_('COM_EASYBOOKRELOADED_ENTRY_DELETED');
				$type = 'success';
			}
		}

		$link = JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.JFactory::getSession()->get('gbid', false, 'easybookreloaded'), false);
		$this->setRedirect($link, $msg, $type);
	}

	/**
	 * Changes the status of the entry - online / offline
	 */
	public function publish()
	{
		// Clean page cache if System Cache plugin is enabled
		if(JPluginHelper::isEnabled('system', 'cache'))
		{
			$this->cleanCache();
		}

		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_RIGHTS');
		$type = 'message';

		if(EASYBOOK_CANEDIT)
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

		$link = JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.JFactory::getSession()->get('gbid', false, 'easybookreloaded'), false);
		$this->setRedirect($link, $msg, $type);
	}

	/**
	 * Saves the comment of the administrator and inform the entry creator
	 */
	public function saveComment()
	{
		// Clean page cache if System Cache plugin is enabled
		if(JPluginHelper::isEnabled('system', 'cache'))
		{
			$this->cleanCache();
		}

		$gbid = JFactory::getSession()->get('gbid', false, 'easybookreloaded');
		$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_RIGHTS');
		$type = 'message';

		if(EASYBOOK_CANEDIT)
		{
			JSession::checkToken() OR jexit('Invalid Token');
			$model = $this->getModel('entry');

			if(!$row = $model->saveComment())
			{
				$msg = JText::_('COM_EASYBOOKRELOADED_ERROR_COULD_NOT_SAVE_COMMENT');
				$type = 'error';
			}
			else
			{
				if(isset($row['inform']) AND $row['inform'] == 1)
				{
					$data = $model->getRow($row['id']);
					$uri = JUri::getInstance();
					$mail = JFactory::getMailer();
					$params = JComponentHelper::getParams('com_easybookreloaded');
					require_once(JPATH_COMPONENT.'/helpers/route.php');

					$href = $uri::base().EasybookReloadedHelperRoute::getEasybookReloadedRoute($data->get('id'), $gbid);

					$mail->setSubject(JText::_('COM_EASYBOOKRELOADED_ADMIN_COMMENT_SUBJECT'));
					$mail->setBody(JText::sprintf('COM_EASYBOOKRELOADED_ADMIN_COMMENT_BODY', $data->get('gbname'), $uri::base(), $href));

					if($params->get('send_mail_html'))
					{
						$mail->isHtml(true);
						$mail->setBody(JText::sprintf('COM_EASYBOOKRELOADED_ADMIN_COMMENT_BODY_HTML', $data->get('gbname'), $uri::base(), $href));
					}

					$config = JFactory::getConfig();

					$mail->addRecipient($data->get('gbmail'));
					$mail->setSender(array($config->get('mailfrom'), $config->get('fromname')));

					// Which mail type should be used? Default is PHP mail
					if($config->get('mailer') == 'sendmail')
					{
						$mail->useSendmail($config->get('sendmail'));
					}
					elseif($config->get('mailer') == 'smtp')
					{
						$mail->useSmtp($config->get('smtpauth'), $config->get('smtphost'), $config->get('smtpuser'), $config->get('smtppass'), $config->get('smtpsecure'), $config->get('smtpport'));
					}

					$mail->Send();

					$msg = JText::_('COM_EASYBOOKRELOADED_COMMENT_SAVED_INFORM');
				}
				else
				{
					$msg = JText::_('COM_EASYBOOKRELOADED_COMMENT_SAVED');
				}

				$type = 'success';
			}
		}

		$link = JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gbid, false);
		$this->setRedirect($link, $msg, $type);
	}
}
