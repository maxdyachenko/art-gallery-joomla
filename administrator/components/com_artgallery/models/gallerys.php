<?php

defined('_JEXEC') or die;


class ArtGalleryModelGallerys extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   1.6
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }


    public function getListQuery()
    {
        $jinput = JFactory::getApplication()->input;
        $id = intval($jinput->get('id'));
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id', 'name')))
            ->from($db->quoteName('#__gallerys_list'))
            ->where($db->quoteName('#__gallerys_list.user_id') . ' = ' . $id);

        return $query;

    }

    public function getImagesCount($gallery_id)
    {
        $jinput = JFactory::getApplication()->input;
        $id = intval($jinput->get('id'));
        $db    = JFactory::getDbo();
        $query ="SELECT COUNT(*) AS number FROM #__users_imgs WHERE gallery_id = {$gallery_id} AND user_id = {$id}";

        $db->setQuery( $query );
        $res = $db->loadObjectList();
        return $res[0]->number;
    }

    public function delete($id)
    {
        $session = JFactory::getSession();
        $user_id = $session->get('art_gallery_user_id');

        $db = JFactory::getDbo();

        $results = array();

        for ($i = 0;$i < count($id); $i++)
        {
            $query = $db->getQuery(true);

            $query->select($db->quoteName(array('fetch_name')));
            $query->from($db->quoteName('#__gallerys_list'));
            $query->where($db->quoteName('id') . ' = ' . $id[$i]);

            $db->setQuery($query);

            array_push($results,$db->loadrow());
            $dirName = JPATH_ROOT. "/components/com_artgallery/media/images/user_id_" . $user_id . "/gallery_" . $results[$i][0];
            $files = glob($dirName . "/*");
            foreach($files as $file){
                if(is_file($file)){
                    unlink($file);
                }
            }
            rmdir($dirName);
        }

        $str = "";
        for ($i = 0;$i < count($id); $i++){
            $str .= $id[$i];
            if ($i != count($id) - 1) {
                $str .= ',';
            }
        }
        $sql = "DELETE FROM `#__gallerys_list` WHERE `id` IN ({$str}) ; DELETE FROM `#__users_imgs` WHERE `gallery_id` IN ({$str}) ;";
        $db    = JFactory::getDbo();
        $queries = $db->splitSql($sql);
        foreach( $queries AS $query ) {
            $db->setQuery($query);
            $db->execute();
        }
        return true;
    }

}
