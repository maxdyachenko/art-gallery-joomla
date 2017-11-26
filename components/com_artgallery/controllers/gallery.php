<?php
defined('_JEXEC') or die('Restricted access');

class ArtGalleryControllerGallery extends JControllerLegacy
{
    public function __construct()
    {
        parent::__construct();
        $this->app = JFactory::getApplication();
        $this->session = JFactory::getSession();
        $this->input = $this->app->input;

        $this->gallery_id = $this->input->getInt('cid', 0);
        $this->user_id = $this->session->get('artgallery_front_user_id');
    }

    public function getModel($name = 'Gallery', $prefix = 'ArtGalleryModel')
    {
        $model = parent::getModel($name, $prefix);

        return $model;
    }

    public function remove()
    {

        $model = $this->getModel();
        if(!$model->delete($this->gallery_id))
        {
            $msg = JText::_('COM_ARTGALLERY_ERROR_GALLERY_COULD_NOT_BE_DELETED');
            $type = 'error';
        }
        else
        {
            $msg = JText::_('COM_ARTGALLERY_GALLERY_DELETED');
            $type = 'success';
        }


        $link = JRoute::_('index.php?option=com_artgallery&view=gallerys', false);
        $this->setRedirect($link, $msg, $type);
    }
}
