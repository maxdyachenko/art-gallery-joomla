<?php

defined('_JEXEC') or die('Restricted access');

class ArtGalleryViewGallery extends JViewLegacy
{
    function display($tpl = null)
    {
        $app		= JFactory::getApplication();
        $dispatcher = JDispatcher::getInstance();

        // Get some data from the models
        $state		= $this->get('State');
        $item		= $this->get('Item');
        $this->form	= $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Display the view
        parent::display($tpl);
    }
}
