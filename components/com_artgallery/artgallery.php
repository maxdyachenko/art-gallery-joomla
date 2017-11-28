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

$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
