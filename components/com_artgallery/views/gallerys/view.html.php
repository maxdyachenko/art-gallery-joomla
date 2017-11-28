<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the HelloWorld Component
 */
class ArtGalleryViewGallerys extends JViewLegacy
{
	// Overwriting JView display method
	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->addHeadData();

        $session = JFactory::getSession();
        $this->id = $session->get('artgallery_front_user_id');

		parent::display($tpl);
	}

    private function addHeadData()
    {
        $document = JFactory::getDocument();


        $document->addStyleSheet(JUri::root().'components/com_artgallery/media/css/artgallery_main.css');

    }
}