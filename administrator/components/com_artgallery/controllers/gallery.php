<?php

defined('_JEXEC') or die;


class ArtGalleryControllerGallery extends JControllerAdmin
{
    public function getModel($name = 'Gallery', $prefix = 'ArtGalleryModel')
    {
        $model = parent::getModel($name, $prefix);

        return $model;
    }

    public function delete()
    {
        $msg = JText::_('OM_ARTGALLERY_DELETED_IMAGES');;
        $type = 'message';

        $mainframe =JFactory::getApplication();
        $id = $mainframe->getUserState( "gallery_edited");
        parent::delete();
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . '&id=' . $id, false), $msg, $type);
    }
}