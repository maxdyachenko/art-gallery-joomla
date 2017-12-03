<?php

// No direct access to this file
defined('_JEXEC') or die;


class ArtGalleryViewGallery extends JViewLegacy
{
    public function display($tpl = null)
    {
        $jinput = JFactory::getApplication()->input;
        $mainframe =JFactory::getApplication();
        $this->gallery_id = intval($jinput->get('id'));
        $mainframe->setUserState( "gallery_edited", $this->gallery_id );
        $session = JFactory::getSession();
        $this->user_id = $session->get('art_gallery_user_id');
        $this->pagination = $this->get('Pagination');
        $this->items = $this->get('Items');

        $this->addToolBar();
        parent::display($tpl);
    }

    protected function addToolBar()
    {
        $document = JFactory::getDocument();
        $title = JText::_('COM_ARTGALLERY_USER_IMAGES');

        JToolBarHelper::deleteList(COM_ARTGALLERY_DELETE_IMAGES, 'gallery.delete');
        JToolBarHelper::title($title);

        $document->addStyleSheet(JUri::root().'components/com_artgallery/media/css/artgallery_main.css');
    }
}