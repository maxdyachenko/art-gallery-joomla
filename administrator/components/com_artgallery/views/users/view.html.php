<?php

// No direct access to this file
defined('_JEXEC') or die;


class ArtGalleryViewUsers extends JViewLegacy
{
    public function display($tpl = null)
    {

        $this->users = $this->get('Items');

        $this->pagination = $this->get('Pagination');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->state = $this->get('State');
        $this->addToolBar();
        parent::display($tpl);
    }
    protected function addToolBar()
    {
        $title = JText::_('COM_ARTGALLERY_USERS_WITH_GALLERIES');


        JToolBarHelper::title($title);
    }

}
