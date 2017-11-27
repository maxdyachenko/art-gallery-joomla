<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class ArtGalleryModelGallery extends JModelForm
{
    /**
     * @var object item
     */
    protected $item;

    /**
     * Get the data for a new qualification
     */
    public function getForm($data = array(), $loadData = true)
    {

        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm('com_artgallery.create', 'create');
        if (empty($form)) {
            return false;
        }
        return $form;

    }


    public function updItem($data)
    {
        // set the variables from the passed data
        $id = $data['id'];
        $greeting = $data['greeting'];

        // set the data into a query to update the record
        $db		= $this->getDbo();
        $query	= $db->getQuery(true);
        $query->clear();
        $query->update(' #__helloworld ');
        $query->set(' greeting = '.$db->Quote($greeting) );
        $query->where(' id = ' . (int) $id );

        $db->setQuery((string)$query);

        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
            return false;
        } else {
            return true;
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM `#__gallerys_list` WHERE `id` = {$id} ; DELETE FROM `#__users_imgs` WHERE `gallery_id` = {$id}";
        $db    = JFactory::getDbo();
        $queries = $db->splitSql($sql);
        foreach( $queries AS $query ) {
            $db->setQuery($query);
            $db->execute();
        }
        return true;
    }

    public function hasLimit($id)
    {
        $db    = JFactory::getDbo();
        $query ="SELECT COUNT(*) AS number FROM #__gallerys_list WHERE user_id = {$id}";

        $db->setQuery($query);
        $res = $db->loadObjectList();
        return $res[0]->number < 5;
    }


}