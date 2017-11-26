<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

class ArtGalleryController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = array())
    {
        $user = JFactory::getUser();
        $app  = JFactory::getApplication();
        if ($user->get('guest') == 1 || $user->get('block') == 1)
        {
            $uri = JUri::getInstance();
            $this->setRedirect(
                JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode($uri->toString())), $app->enqueueMessage(JText::_('COM_ARTGALLERY_LOGIN_REQUIRED'), 'warning')
            );

            return;
        }


        parent::display($cachable, $urlparams);
    }
}