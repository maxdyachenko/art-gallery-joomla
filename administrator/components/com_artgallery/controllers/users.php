<?php

defined('_JEXEC') or die;


class ArtGalleryControllerUsers extends JControllerAdmin
{
    public function getModel($name = 'Users', $prefix = 'ArtGalleryModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}
