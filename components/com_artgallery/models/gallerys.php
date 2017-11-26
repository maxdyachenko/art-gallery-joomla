<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modellist');


class ArtGalleryModelGallerys extends JModelList
{

    public function getListQuery()
    {
        $session = JFactory::getSession();
        $id = $session->get('artgallery_front_user_id');
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id', 'name', 'avatar')))
            ->from($db->quoteName('#__gallerys_list'))
            ->where($db->quoteName('#__gallerys_list.user_id') . ' = ' . $id);

        return $query;
    }

}