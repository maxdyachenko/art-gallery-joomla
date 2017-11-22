<?php

defined('_JEXEC') or die;


class ArtGalleryControllerUsers extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Users', $prefix = 'ArtGalleryModel')
	{
		$model = parent::getModel($name, $prefix);

		return $model;
	}
}
