<?php

// No direct access to this file
defined('_JEXEC') or die;


class ArtGalleryViewGallerys extends JViewLegacy
{
    protected $count = array();

    public function display($tpl = null)
    {
        $jinput = JFactory::getApplication()->input;
        $id = intval($jinput->get('id'));
        $session = JFactory::getSession();
        $session->set('art_gallery_user_id', $id);

        $this->items = $this->get('Items');

        $model = $this->getModel();

        foreach ($this->items as $item)
        {
            array_push($this->count, $model->getImagesCount($item->id));
        }
        $this->addToolBar();
        parent::display($tpl);
    }

    protected function addToolBar()
    {
        $title = JText::_('COM_ARTGALLERY_USER_GALLERYS');

        JToolBarHelper::deleteList(COM_ARTGALLERY_DELETE_GALLERY, 'gallerys.delete');
        JToolBarHelper::title($title);
    }

}
