<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidator');

?>

<div class="content">
    <section class="news-container content-container">
        <div class="row justify-content-between images-wrapper">
            <div class="image-container col-12 col-md-4 add-image-block">
                <form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_artgallery'); ?>" enctype="multipart/form-data" method="post" id="add" name="add">
                    <div class="button-container">
                        <input type="file" name="file" id="file" size="12" accept="image/*" class="validate-file required add-button input-file" required="required" aria-required="true" aria-invalid="true" />
                        <label for="file">
                            <figure></figure>
                            <p>Choose file...</p>
                        </label>
                    </div>
                    <input type="hidden" name="option" value="com_artgallery" />
                    <input type="hidden" name="MAX_FILE_SIZE" value="2000">
                    <input type="hidden" name="task" value="add"/>
                    <input type="hidden" name="controller" value="edit"/>
                    <input type="hidden" name="id" value="<?php echo $this->gallery_id; ?>"/>
                    <button type="submit" class="btn btn-primary validate" name="upload-image">Upload</button>
                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </div>
            <?php foreach ($this->items as $item) : ?>
                <div class="image-container col-12 col-md-4">
                    <div class="image">
                        <img src="<?php echo JURI::root()  . 'components/com_artgallery/media/images/user_id_' . $this->user_id . '/gallery_' . $item->gallery_fetch . '/' . $item->user_img ?>" alt="Your image" class="rounded">
                    </div>
                    <div class="custom-popover">
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="chk[]" value="<?php //echo $content_item['user_img']; ?>">
                            </label>
                        </div>
                        <button type="button" class="btn btn-danger">Delete image</button>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php echo $this->pagination->getListFooter() ?>
        </div>
    </section>
</div>
