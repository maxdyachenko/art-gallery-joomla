<?php

// No direct access to this file
defined('_JEXEC') or die;


class ArtGalleryViewGallerys extends JViewLegacy
{
    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        var_dump($this->items);
        parent::display($tpl);
    }
}
