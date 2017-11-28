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
        $this->setDocument();
    }

    public function setDocument()
    {
        $document = JFactory::getDocument();

        $css_file = 'artgallery_main';

        $document->addStyleSheet(JUri::root().'components/com_artgallery/media/css/'.$css_file.'.css');
        $document->addScript(JUri::root(TRUE) . '/components/com_artgallery/media/js/name.js');
        $document->addScript(JUri::root(TRUE) . '/components/com_artgallery/media/js/file.js');
    }

}
