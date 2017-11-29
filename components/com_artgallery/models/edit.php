<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');



class ArtGalleryModelEdit extends JModelList
{

    public function getListQuery()
    {
        $session = JFactory::getSession();
//        $id = $session->get('artgallery_front_user_id');
        $gallery_id = $session->get('artgallery_gallery_edit');
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id', 'gallery_fetch', 'user_img')))
            ->from($db->quoteName('#__users_imgs'))
            ->where($db->quoteName('gallery_id') . ' = ' . $gallery_id);

        return $query;
    }
}