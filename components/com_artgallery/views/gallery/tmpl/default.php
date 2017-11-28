<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidator');

?>
<h2>Create your gallery</h2>

<form class="form-validate" action="<?php echo JRoute::_('index.php?option=com_artgallery'); ?>" enctype="multipart/form-data" method="post" id="create" name="create">
    <fieldset>
        <dl>
            <dt><?php echo $this->form->getLabel('name'); ?></dt>
            <dd><?php echo $this->form->getInput('name'); ?></dd>
            <span><?php echo JText::_('Min 2 chars, max 16 chars, only symbols'); ?></span>

            <dt><?php echo $this->form->getLabel('avatar'); ?></dt>
            <dd><?php echo $this->form->getInput('avatar'); ?></dd>
            <span><?php echo JText::_('Max 2mb, only images allowed'); ?></span>

            <dd><input type="hidden" name="option" value="com_artgallery" />
                <input type="hidden" name="MAX_FILE_SIZE" value="2000">
                <input type="hidden" name="task" value="submit"/>
                <input type="hidden" name="controller" value="gallery"/>
            </dd>

            <dd><button type="submit" class="button validate"><?php echo JText::_('Submit'); ?></button>
                <?php echo JHtml::_('form.token'); ?>
            </dd>
        </dl>
    </fieldset>
</form>
<div class="clr"></div>
