<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.'/controller.php');

if($controller = JFactory::getApplication()->input->getWord('controller', ''))
{
    $path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';

    if(file_exists($path))
    {
        require_once $path;
    }
}

$classname = 'ArtGalleryController'.$controller;
$controller = new $classname();
$user = JFactory::getUser();
$app  = JFactory::getApplication();
if ($user->get('guest') == 1 || $user->get('block') == 1)
{
    $uri = JUri::getInstance();
    $app->redirect(
        JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode($uri->toString())), $app->enqueueMessage(JText::_('COM_ARTGALLERY_LOGIN_REQUIRED'), 'warning')
    );
}
else
{
    $session = JFactory::getSession();
    $session->set('artgallery_front_user_id', $user->get('id'));
    $controller->execute(JFactory::getApplication()->input->get('task'));
    $controller->redirect();
}

