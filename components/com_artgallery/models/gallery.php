<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class ArtGalleryModelGallery extends JModelForm
{


    public function getForm($data = array(), $loadData = true)
    {

        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_artgallery.create', 'create');
        if (empty($form)) {
            return false;
        }
        return $form;

    }


    public function delete($id)
    {
        $session = JFactory::getSession();
        $user_id = $session->get('artgallery_front_user_id');

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select($db->quoteName(array('fetch_name')));
        $query->from($db->quoteName('#__gallerys_list'));
        $query->where($db->quoteName('id') . ' = ' . $id);

        $db->setQuery($query);

        $results = $db->loadObjectList();

        $fetch_name = $results[0]->fetch_name;

        $sql = "DELETE FROM `#__gallerys_list` WHERE `id` = {$id} ; DELETE FROM `#__users_imgs` WHERE `gallery_id` = {$id}";
        $db    = JFactory::getDbo();
        $queries = $db->splitSql($sql);
        foreach( $queries AS $query ) {
            $db->setQuery($query);
            $db->execute();
        }

        $dirName = JPATH_BASE. "/components/com_artgallery/media/images/user_id_" . $user_id . "/gallery_" . $fetch_name;

        $files = glob($dirName . "/*");
        foreach($files as $file){
            if(is_file($file)){
                unlink($file);
            }
        }
        rmdir($dirName);

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

    public function validate($data)
    {
        if (!preg_match('~^[A-Za-z]{2,16}$~', $data[0]))
        {
            $this->setError(JText::_(COM_ARTGALLERY_NAME_ERROR));
            return false;
        }
        if ($data[1]['size'] > 200000)
        {
            $this->setError(JText::_(COM_ARTGALLERY_FILE_ERROR));
            return false;
        }
        if ($data[1]['type'] == 'image/jpeg' || $data[1]['type'] == 'image/png' || $data[1]['type'] == 'image/gif')
        {
            return $data;
        }
        else
        {
            $this->setError(JText::_(COM_ARTGALLERY_FILE_TYPE_ERROR));
            return false;
        }

    }

    public function save($data)
    {
        $name = $data[0];
        $avatar = $data[1];
        $fetch = $data[2];
        $session = JFactory::getSession();
        $user_id = $session->get('artgallery_front_user_id');

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $columns = array('user_id', 'name', 'avatar', 'fetch_name');
        $values = array($user_id, $db->quote($name), $db->quote($avatar), $fetch);
        $query
            ->insert($db->quoteName('#__gallerys_list'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
        $db->setQuery($query);
        if ($db->execute())
            return true;
        return false;

    }


}