<?php

defined('_JEXEC') or die;


class ArtGalleryControllerGallerys extends JControllerAdmin
{
    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   1.6
     */
    public function getModel($name = 'Gallerys', $prefix = 'ArtGalleryModel')
    {
        $model = parent::getModel($name, $prefix);

        return $model;
    }

    public function delete()
    {
        parent::delete();
        $session = JFactory::getSession();
        $this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . '&id=' . $session->get('art_gallery_user_id'), false));
    }

}
