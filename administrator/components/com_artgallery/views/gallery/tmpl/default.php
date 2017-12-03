<?php

// No direct access to this file
defined('_JEXEC') or die;


?>
<form action="index.php?option=com_artgallery&view=gallery&id=<?php echo $this->gallery_id ?>" method="post" id="adminForm" name="adminForm">
    <div class="container container-gal">
        <?php foreach ($this->items as $i =>$item): ?>

            <div class="image-block">
                <span><?php echo $i+1; ?></span>

                <div class="chk">
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </div>

                <img src="<?php echo JURI::root()  . 'components/com_artgallery/media/images/user_id_' . $this->user_id . '/gallery_' . $item->gallery_fetch . '/' . $item->user_img ?>" alt="Your image" class="rounded">

            </div>
        <?php endforeach; ?>
    </div>
    <?php echo $this->pagination->getListFooter(); ?>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

