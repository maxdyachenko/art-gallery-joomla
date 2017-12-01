<?php

defined('_JEXEC') or die('Restricted access');

class ArtGalleryViewGallery extends JViewLegacy
{
    function display($tpl = null)
    {
        $app		= JFactory::getApplication();
        $dispatcher = JDispatcher::getInstance();

        $state		= $this->get('State');
        $item		= $this->get('Item');
        $this->form	= $this->get('Form');


        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        parent::display($tpl);
        $this->setDocument();
    }

    public function setDocument()
    {
        $document = JFactory::getDocument();

        $document->addStyleSheet(JUri::root().'components/com_artgallery/media/css/artgallery_main.css');
        $document->addScript(JUri::root() . 'components/com_artgallery/media/js/name.js');
        $document->addScript(JUri::root() . 'components/com_artgallery/media/js/file.js');
    }

}
