<?php

defined('_JEXEC') or die;


class ArtGalleryModelUsers extends JModelList
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
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'username'
            );
        }
		parent::__construct($config);
	}

    protected function populateState($ordering = null, $direction = null) {
        parent::populateState('username', 'ASC');
    }

    public function delete($id)
    {

        $db = JFactory::getDbo();

        $results = array();


        for ($i = 0;$i < count($id); $i++)
        {
            $query = $db->getQuery(true);

            $query->select($db->quoteName('fetch_name'));
            $query->from($db->quoteName('#__gallerys_list'));
            $query->where($db->quoteName('user_id') . ' = ' . $id[$i]);

            $db->setQuery($query);

            array_push($results,$db->loadObjectList());

            for ($j = 0; $j < count($results[0]); $j++)
            {
                $dirName = JPATH_ROOT. "/components/com_artgallery/media/images/user_id_" . $id[$i] . "/gallery_" . $results[0][$j]->fetch_name;
                $files = glob($dirName . "/*");
                foreach($files as $file){
                    if(is_file($file)){
                        unlink($file);
                    }
                }
                rmdir($dirName);
            }

        }
        $str = "";
        for ($i = 0;$i < count($id); $i++){
            $str .= $id[$i];
            if ($i != count($id) - 1) {
                $str .= ',';
            }
        }
        $sql = "DELETE FROM `#__users_imgs` WHERE `user_id` IN ({$str}) ; DELETE FROM `#__gallerys_list` WHERE `user_id` IN ({$str}) ;";
        $db    = JFactory::getDbo();
        $queries = $db->splitSql($sql);
        foreach( $queries AS $query ) {
            $db->setQuery($query);
            $db->execute();
        }
        return true;
    }


    public function publish($cid, $value)
    {
        $id = $cid[0];
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update($db->quoteName('#__users'))->set($db->quoteName('block') . ' = ' . $value)->where($db->quoteName('id') . '=' . $id);
        $db->setQuery($query);
        $db->execute();

        return true;
    }

    public function getListQuery()
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('#__users.id','#__users.block','#__users.username', '#__users.email')))
            ->from($db->quoteName('#__users'))
            ->join('INNER', $db->quoteName('#__gallerys_list') . ' ON (' . $db->quoteName('#__users.id') . ' = ' . $db->quoteName('#__gallerys_list.user_id') . ')')
            ->group($db->quoteName('#__users.id'));
        $search = $this->getState('filter.search');

        if (!empty($search))
        {
            $like = $db->quote('%' . $search . '%');
            $query->where('username LIKE ' . $like);
        }

        $orderCol	= $this->state->get('list.ordering', 'username');
        $orderDirn 	= $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;

    }

}
