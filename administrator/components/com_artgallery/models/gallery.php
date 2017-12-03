<?php

defined('_JEXEC') or die;


class ArtGalleryModelGallery extends JModelList
{

    public function getListQuery()
    {
        $jinput = JFactory::getApplication()->input;
        $id = intval($jinput->get('id'));
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id', 'user_img', 'gallery_fetch')))
            ->from($db->quoteName('#__users_imgs'))
            ->where($db->quoteName('gallery_id') . ' = ' . $id);


        return $query;

    }

    public function delete($id)
    {
        $session = JFactory::getSession();
        $user_id = $session->get('art_gallery_user_id');

        $db = JFactory::getDbo();

        $fetch_name = $this->getFetchName($id[0]);
        $dirName = JPATH_ROOT. "/components/com_artgallery/media/images/user_id_" . $user_id . "/gallery_" . $fetch_name;

        $imgs_array = $this->getImagesById($id);


        for ($i = 0;$i < count($imgs_array); $i++)
        {
            $file = $dirName . "/" . $imgs_array[$i]->user_img;
            if(is_file($file)){
                unlink($file);
            }
        }

        $str = "";
        for ($i = 0;$i < count($id); $i++){
            $str .= "'";
            $str .= $id[$i];
            $str .= "'";
            if ($i != count($id) - 1) {
                $str .= ',';
            }
        }
        $sql = "DELETE FROM `#__users_imgs` WHERE `id` IN ({$str}) ;";
        $db    = JFactory::getDbo();
        $db->setQuery($sql);

        if ($db->execute())
            return true;

        $this->setError(JText::_(COM_ARTGALLERY_ERROR));
        return false;
    }

    public function getImagesById($id)
    {
        $str = implode(',', $id);

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select($db->quoteName('user_img'));
        $query->from($db->quoteName('#__users_imgs'));
        $query->where($db->quoteName('id') . ' IN (' . $str . ')');

        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function getFetchName($id)
    {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select($db->quoteName('gallery_fetch'));
        $query->from($db->quoteName('#__users_imgs'));
        $query->where($db->quoteName('id') . ' = ' . $id);

        $db->setQuery($query);

        $results = $db->loadRow();
        return $results[0];
    }
}