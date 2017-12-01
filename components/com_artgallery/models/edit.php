<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');



class ArtGalleryModelEdit extends JModelList
{

    public function getListQuery()
    {
        $session = JFactory::getSession();
        $gallery_id = $session->get('artgallery_gallery_edit');
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id', 'gallery_fetch', 'user_img')))
            ->from($db->quoteName('#__users_imgs'))
            ->where($db->quoteName('gallery_id') . ' = ' . $gallery_id);

        return $query;
    }

    public function validate($file)
    {
        if ($file['size'] > 200000)
        {
            $this->setError(JText::_(COM_ARTGALLERY_FILE_ERROR));
            return false;
        }
        if ($file['type'] == 'image/jpeg' || $file['type'] == 'image/png' || $file['type'] == 'image/gif')
        {
            return $file;
        }
        else
        {
            $this->setError(JText::_(COM_ARTGALLERY_FILE_TYPE_ERROR));
            return false;
        }
    }

    public function deleteSelected($imgs, $user_id, $gallery_id)
    {
        $db = JFactory::getDbo();

        $fetch = $this->getFetchName($gallery_id);

        $dirName = JPATH_ROOT. "/components/com_artgallery/media/images/user_id_" . $user_id . "/gallery_" . $fetch;

        $imgs_array = explode(',', $imgs);


        for ($i = 0;$i < count($imgs_array); $i++)
        {
            $file = $dirName . "/" . $imgs_array[$i];
            if(is_file($file)){
                unlink($file);
            }
        }

        $str = "";
        for ($i = 0;$i < count($imgs_array); $i++){
            $str .= "'";
            $str .= $imgs_array[$i];
            $str .= "'";
            if ($i != count($imgs_array) - 1) {
                $str .= ',';
            }
        }
        $sql = "DELETE FROM `#__users_imgs` WHERE `user_img` IN ({$str}) AND `user_id` = {$user_id};";
        $db->setQuery($sql);
        if ($db->execute())
        {
            return true;
        }

        return false;
    }

    public function deleteAll($user_id, $gallery_id)
    {
        $fetch = $this->getFetchName($gallery_id);

        $dirName = JPATH_ROOT. "/components/com_artgallery/media/images/user_id_" . $user_id . "/gallery_" . $fetch;
        $files = glob($dirName . "/*");
        foreach($files as $file){
            if(is_file($file) && strpos($file, $fetch) == false){
                unlink($file);
            }
        }

        $db = JFactory::getDbo();

        $sql = "DELETE FROM `#__users_imgs` WHERE `user_id` = {$user_id} AND `gallery_id` = {$gallery_id};";
        $db->setQuery($sql);
        if ($db->execute())
        {
            return true;
        }

        return false;


    }

    public function remove($img_id, $user_id)
    {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select($db->quoteName(array('gallery_fetch', 'user_img')));
        $query->from($db->quoteName('#__users_imgs'));
        $query->where($db->quoteName('id') . ' = ' . $img_id);

        $db->setQuery($query);

        $results = $db->loadObjectList();

        $fetch_name = $results[0]->gallery_fetch;
        $user_img = $results[0]->user_img;



        $sql = "DELETE FROM `#__users_imgs` WHERE `id` = {$img_id}";
        $db    = JFactory::getDbo();
        $db->setQuery($sql);
        if (!$db->execute())
        {
            $this->setError(JText::_(COM_ARTGALLERY_ERROR));
            return false;
        }


        $file = JPATH_BASE. "/components/com_artgallery/media/images/user_id_" . $user_id . "/gallery_" . $fetch_name . '/' . $user_img ;

        if(is_file($file)){
            unlink($file);
        }


        return true;
    }

    public function checkImgId($img_id, $user_id)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select($db->quoteName('gallery_fetch'));
        $query->from($db->quoteName('#__users_imgs'));
        $query->where($db->quoteName('user_id') . ' = ' . $user_id);
        $query->where($db->quoteName('id') . ' = ' . $img_id);

        $db->setQuery($query);

        $results = $db->loadRow();
        if (!$results)
        {
            $this->setError(JText::_(COM_ARTGALLERY_ERROR));
            return false;
        }
        return true;
    }


    public function getFetchName($id)
    {

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select($db->quoteName('fetch_name'));
        $query->from($db->quoteName('#__gallerys_list'));
        $query->where($db->quoteName('id') . ' = ' . $id);

        $db->setQuery($query);

        $results = $db->loadRow();
        return $results[0];
    }

    public function haveUserGallery($gallery_id, $user_id)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select($db->quoteName('fetch_name'));
        $query->from($db->quoteName('#__gallerys_list'));
        $query->where($db->quoteName('user_id') . ' = ' . $user_id);
        $query->where($db->quoteName('id') . ' = ' . $gallery_id);

        $db->setQuery($query);

        $results = $db->loadRow();
        if (!$results)
        {
            $this->setError(JText::_(COM_ARTGALLERY_ERROR));
            return false;
        }
        return true;
    }

    public function save($filename, $user_id, $fetch, $gallery_id)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $columns = array('user_id','user_img', 'gallery_fetch', 'gallery_id');
        $values = array($user_id, $db->quote($filename), $fetch, $gallery_id);
        $query
            ->insert($db->quoteName('#__users_imgs'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
        $db->setQuery($query);
        if ($db->execute())
            return true;
        return false;
    }
}