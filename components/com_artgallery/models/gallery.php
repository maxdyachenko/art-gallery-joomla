<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class ArtGalleryModelGallery extends JModelItem
{

    public function delete($id)
    {
        $sql = "DELETE FROM `#__gallerys_list` WHERE `id` = {$id} ; DELETE FROM `#__users_imgs` WHERE `gallery_id` = {$id}";
        $db    = JFactory::getDbo();
        $queries = $db->splitSql($sql);
        foreach( $queries AS $query ) {
            $db->setQuery($query);
            $db->execute();
        }
        return true;
    }

    public function hasLimit($id)
    {
        $db    = JFactory::getDbo();
        $query ="SELECT COUNT(*) AS number FROM #__gallerys_list WHERE user_id = {$id}";

        $db->setQuery($query);
        $res = $db->loadObjectList();
        return $res[0]->number < 5;
    }


}