<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
class ArtGalleryControllerGallery extends JControllerForm
{
    public function __construct()
    {
        parent::__construct();
        $this->model = $this->getModel();
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
        if(!$this->model->delete($this->gallery_id))
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

    public function create()
    {
        if($this->model->hasLimit($this->user_id))
        {
            $this->input->set('view', 'gallery');
            $this->input->set('layout', 'create');
            parent::display();

            return;
        }

        $link = JRoute::_('index.php?option=com_artgallery&view=gallerys', false);
        $msg = JText::_('COM_ARTGALLERY_ERROR_LIMIT');
        $type = 'message';
        $this->setRedirect($link, $msg, $type);
    }

    public function submit()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app        = JFactory::getApplication();
        $jinput = JFactory::getApplication()->input;
        $name = $jinput->get('name');
        $file  = $jinput->files->get('avatar');
        $data = array();
        array_push($data, $name, $file);
        $data = $this->model->validate($data);

        if ($data === false)
        {
            $error = $this->model->getErrors();
            $app->enqueueMessage($error[0], 'warning');
            return false;
        }
        $session = JFactory::getSession();
        $id = $session->get('artgallery_front_user_id');
        $fetch_name = time();


        $dirName = JPATH_BASE. "/components/com_artgallery/media/images/user_id_" . $id . "/gallery_" . $fetch_name;
        !file_exists($dirName) ? mkdir($dirName, 0777, true) : false;

        $filename = JFile::makeSafe($data[1]['name']);

        $dest = $dirName . '/' . $filename;
        $src  = $data[1]['tmp_name'];

        if (!JFile::upload($src, $dest))
        {
            $app->enqueueMessage(JText::_('File was not uploaded'), 'warning');
            return false;
        }

        //$this->model->save($data);
        
    }
}
