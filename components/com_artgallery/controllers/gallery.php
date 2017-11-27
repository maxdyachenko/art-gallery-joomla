<?php
defined('_JEXEC') or die('Restricted access');

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
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app	= JFactory::getApplication();
        $model	= $this->getModel();

        // Get the data from the form POST
        $data = JRequest::getVar('jform', array(), 'post', 'array');

        // Now update the loaded data to the database via a function in the model
        //$upditem	= $model->updItem($data);

        // check if ok and display appropriate message.  This can also have a redirect if desired.
        if ($upditem) {
            echo "<h2>Updated Greeting has been saved</h2>";
        } else {
            echo "<h2>Updated Greeting failed to be saved</h2>";
        }

        return true;
    }
}
