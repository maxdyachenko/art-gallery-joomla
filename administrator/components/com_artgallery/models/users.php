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

    public function getListQuery()
    {
        // Initialize variables.
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Create the base select statement.
        $query->select($db->quoteName(array('#__users.id','#__users.username', '#__users.email')))
            ->from($db->quoteName('#__users'))
            ->join('INNER', $db->quoteName('#__gallerys_list') . ' ON (' . $db->quoteName('#__users.id') . ' = ' . $db->quoteName('#__gallerys_list.user_id') . ')')
            ->group($db->quoteName('#__users.id'));
        // Filter: like / search
        $search = $this->getState('filter.search');

        if (!empty($search))
        {
            $like = $db->quote('%' . $search . '%');
            $query->where('username LIKE ' . $like);
        }

        // Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering', 'username');
        $orderDirn 	= $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;

    }

}
