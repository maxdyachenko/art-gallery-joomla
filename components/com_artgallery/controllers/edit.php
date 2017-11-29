<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
class ArtGalleryControllerEdit extends JControllerLegacy
{
    public function __construct()
    {
        parent::__construct();
        $this->model = $this->getModel();
        $this->app = JFactory::getApplication();

        $this->session = JFactory::getSession();
        $this->input = $this->app->input;

        $this->gallery_id = $this->input->getInt('id', 0);
        $this->session->set('artgallery_gallery_edit', $this->gallery_id);
        $this->user_id = $this->session->get('artgallery_front_user_id');
    }

    public function getModel($name = 'Edit', $prefix = 'ArtGalleryModel')
    {
        $model = parent::getModel($name, $prefix);

        return $model;
    }
}