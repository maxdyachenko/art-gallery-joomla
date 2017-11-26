<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class ArtGalleryModelGallerys extends JModelList
{
	protected $msg;


	public function getMsg()
	{
		if (!isset($this->msg))
		{
			$this->msg = 'Hello World121212!';
		}

		return $this->msg;
	}
}