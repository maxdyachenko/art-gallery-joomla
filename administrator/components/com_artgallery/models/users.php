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
		parent::__construct($config);
	}

    public function getUsers()
    {
        $registeredUsers = JAccess::getUsersByGroup(2); // in my project it was $self::REGISTERED_GROUP
        return $registeredUsers;
    }

}
