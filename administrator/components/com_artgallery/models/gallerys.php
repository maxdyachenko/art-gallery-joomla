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


    public function getItems()
    {
        $jinput = JFactory::getApplication()->input;
        $user_id = $jinput->get('id');
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('id, user_id, name, avatar');
        $query->where('user_id',$user_id);
        $query->from($db->quoteName('#__gallerys_list'));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

}
