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
    public function display()
    {
        if (!$this->checkGalleryId($this->gallery_id, $this->user_id))
        {
            return;
        }
        parent::display();
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

        $this->img_id = intval($this->input->getInt('imgid', 0));
        $gid = intval($this->input->getInt('gid', 0));
        if (!$this->model->checkImgId($this->img_id, $this->user_id))
        {
            $error = $this->model->getErrors();
            $link = JRoute::_('index.php?option=com_artgallery', false);
            $msg = $error[0];
            $type = 'error';
            $this->setRedirect($link, $msg, $type);
            return;
        }

        if(!$this->model->remove($this->img_id, $this->user_id))
        {
            $msg = JText::_('COM_ARTGALLERY_ERROR');
            $type = 'error';
        }
        else
        {
            $msg = JText::_('COM_ARTGALLERY_IMG_DELETED');
            $type = 'success';
        }


        $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $gid, false);
        $this->setRedirect($link, $msg, $type);
    }

    public function delete()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $gallery_id = intval($this->input->getInt('id', 0));

        if (!$this->checkGalleryId($gallery_id, $this->user_id))
        {
            return;
        }

        $jinput = JFactory::getApplication()->input;
        $chk  = $jinput->post->get('imgs');

        if (!$chk)
        {
            $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $gallery_id, false);
            $msg = JText::_('COM_ARTGALLERY_ERROR');;
            $type = 'error';
            $this->setRedirect($link, $msg, $type);
        }

        $chk = implode(',', $chk);

        if ($this->model->deleteSelected($chk, $this->user_id, $gallery_id))
        {
            $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $gallery_id, false);
            $msg = JText::_('COM_ARTGALLERY_IMAGES_DELETED');;
            $type = 'message';
            $this->setRedirect($link, $msg, $type);
            return true;
        }

        $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $gallery_id, false);
        $msg = JText::_('COM_ARTGALLERY_ERROR');;
        $type = 'error';
        $this->setRedirect($link, $msg, $type);

    }

    public function deleteAll()
    {
        $gallery_id = intval($this->input->getInt('gid', 0));

        if (!$this->checkGalleryId($gallery_id, $this->user_id))
        {
            return;
        }

        if ($this->model->deleteAll($this->user_id, $gallery_id))
        {
            $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $gallery_id, false);
            $msg = JText::_('COM_ARTGALLERY_IMAGES_DELETED');;
            $type = 'message';
            $this->setRedirect($link, $msg, $type);
            return true;
        }
        $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $gallery_id, false);
        $msg = JText::_('COM_ARTGALLERY_ERROR');;
        $type = 'error';
        $this->setRedirect($link, $msg, $type);

    }


    public function add()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app        = JFactory::getApplication();
        $jinput = JFactory::getApplication()->input;
        $file  = $jinput->files->get('file');

        $gallery_id = $this->input->getInt('id', 0);

        if (!$this->checkGalleryId($gallery_id, $this->user_id))
        {
            return;
        }

        $data = $this->model->validate($file);

        if ($data === false)
        {
            $error = $this->model->getErrors();
            $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $this->gallery_id, false);
            $msg = $error[0];
            $type = 'error';
            $this->setRedirect($link, $msg, $type);
            return;
        }

        $fetch_name = $this->model->getFetchName(intval($gallery_id), $this->user_id);

        $image_name = time();

        $dirName = JPATH_BASE. "/components/com_artgallery/media/images/user_id_" . $this->user_id . "/gallery_" . $fetch_name;

        $filename = $image_name . '.' . JFile::getExt($data['name']);

        $dest = $dirName . '/' . $filename;
        $src  = $data['tmp_name'];

        if (!JFile::upload($src, $dest))
        {
            $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $this->gallery_id, false);
            $msg = JText::_('COM_ARTGALLERY_FILE_WAS_NOT_UPLOADED');
            $type = 'error';
            $this->setRedirect($link, $msg, $type);
            return;

        }
        if (!$this->model->save($filename, $this->user_id, $fetch_name, $gallery_id))
        {
            $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $this->gallery_id, false);
            $msg = JText::_('Image was not saved');
            $type = 'error';
            $this->setRedirect($link, $msg, $type);
            return;
        }
        $link = JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id=' . $this->gallery_id, false);
        $msg = JText::_('COM_ART_GALLERY_IMAGE_SAVED');
        $type = 'message';
        $this->setRedirect($link, $msg, $type);

    }


}