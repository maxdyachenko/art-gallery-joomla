<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<div class="content1">
    <section class="news-container content-container">
        <a href="<?php echo JRoute::_('index.php?option=com_artgallery&controller=gallery&task=create') ?>" class="btn btn-primary">Create gallery</a>
        <?php foreach ($this->items as $item): ?>
            <div class="card card-custom">
                <img class="card-img-top" src="<?php echo JURI::root()  . 'components/com_artgallery/media/images/user_id_' . $this->id . '/gallery_' . $item->fetch_name . '/' . $item->avatar ?>" alt="Card image cap">
                <div class="card-block">
                    <h4 class="card-title"><?php echo $item->name; ?></h4>
                    <div class="buttons-group">
                        <a href="<?php echo JRoute::_('index.php?option=com_artgallery&controller=edit&view=edit&id='.(int)$item->id); ?>" class="btn btn-primary"><?php  echo JText::_(COM_ARTGALLERY_OPEN) ?></a>
                        <a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_artgallery&controller=gallery&task=remove&cid='.(int)$item->id); ?>">
                            <?php  echo JText::_(COM_ARTGALLERY_DELETE_GALLERY) ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

</div>