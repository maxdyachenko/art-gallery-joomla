<?php

defined('_JEXEC') or die('Restricted access');

class ArtGalleryViewEdit extends JViewLegacy
{
    public function display($tpl = null)
    {

        $state		= $this->get('State');
        $this->items = $this->get('Items');

        $this->session = JFactory::getSession();
        $this->user_id = $this->session->get('artgallery_front_user_id');

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
        $document->addScript(JUri::root() . 'components/com_artgallery/media/js/file.js');
    }

}
