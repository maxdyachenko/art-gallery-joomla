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

    public function checkGalleryId($gallery_id, $user_id)
    {
        if (!$this->model->haveUserGallery($gallery_id, $user_id))
        {
            $error = $this->model->getErrors();
            $link = JRoute::_('index.php?option=com_artgallery', false);
            $msg = $error[0];
            $type = 'error';
            $this->setRedirect($link, $msg, $type);
            return false;
        }
        return true;
    }

    public function remove()
    {
        if (!$this->checkGalleryId($this->gallery_id, $this->user_id))
        {
            return;
        }
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
            $link = JRoute::_('index.php?option=com_artgallery&controller=gallery&task=create', false);
            $msg = $error[0];
            $type = 'error';
            $this->setRedirect($link, $msg, $type);
            return;
        }
        $session = JFactory::getSession();
        $id = $session->get('artgallery_front_user_id');
        $fetch_name = time();


        $dirName = JPATH_BASE. "/components/com_artgallery/media/images/user_id_" . $id . "/gallery_" . $fetch_name;
        !file_exists($dirName) ? mkdir($dirName, 0777, true) : false;

        $filename = $fetch_name . '.' . JFile::getExt($data[1]['name']);

        $dest = $dirName . '/' . $filename;
        $src  = $data[1]['tmp_name'];

        if (!JFile::upload($src, $dest))
        {
            $link = JRoute::_('index.php?option=com_artgallery&controller=gallery&task=create', false);
            $msg = JText::_('File was not uploaded');
            $type = 'error';
            $this->setRedirect($link, $msg, $type);

        }
        array_push($data, $fetch_name);
        $data[1] = $filename;
        if (!$this->model->save($data))
        {
            $link = JRoute::_('index.php?option=com_artgallery&controller=gallery&task=create', false);
            $msg = JText::_('Gallery was not saved');
            $type = 'error';
            $this->setRedirect($link, $msg, $type);
        }
        $link = JRoute::_('index.php?option=com_artgallery&view=gallerys', false);
        $msg = JText::_('Gallery was saved');
        $type = 'message';
        $this->setRedirect($link, $msg, $type);

    }
}
