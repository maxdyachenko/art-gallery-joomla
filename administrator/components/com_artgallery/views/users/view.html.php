<?php

// No direct access to this file
defined('_JEXEC') or die;


class ArtGalleryViewUsers extends JViewLegacy
{
    public function display($tpl = null)
    {
//        echo "<pre>";
//        print_r(JLoader::getClassList());die;
        // Get data from the model

        $this->users = $this->get('Users');
        for($i = 0; $i < count($this->users); $i++) {
            $this->users[$i] = JFactory::getUser($this->users[$i]);
        }
//                echo "<pre>";
//        print_r($this->users);die;

        // Display the template
        parent::display($tpl);
    }
}
