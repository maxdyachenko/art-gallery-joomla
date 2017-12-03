<?php

// No direct access to this file
defined('_JEXEC') or die;


class ArtGalleryViewGallery extends JViewLegacy
{
    public function display($tpl = null)
    {

        //$this->items = $this->get('Items');

        $this->addToolBar();
        parent::display($tpl);
    }

    protected function addToolBar()
    {
        $title = JText::_('COM_ARTGALLERY_USER_IMAGES');

        JToolBarHelper::deleteList(COM_ARTGALLERY_DELETE_IMAGES, 'gallerys.delete');
        JToolBarHelper::title($title);
    }
}