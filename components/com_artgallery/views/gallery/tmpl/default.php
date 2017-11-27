<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.tooltip');

?>
<h2>Update the Hello World greeting</h2>

<form class="form-validate" action="<?php echo JRoute::_('index.php'); ?>" method="post" id="create" name="create">
    <fieldset>
        <dl>
            <dt><?php echo $this->form->getLabel('myname'); ?></dt>
            <dd><?php echo $this->form->getInput('myname'); ?></dd>
            <dt></dt><dd></dd>
            <dt><?php echo $this->form->getLabel('myavatar'); ?></dt>
            <dd><?php echo $this->form->getInput('myavatar'); ?></dd>
            <dt></dt><dd></dd>
            <dt></dt>
            <dd><input type="hidden" name="option" value="com_artgallery" />
                <input type="hidden" name="task" value="create.submit" />
            </dd>
            <dt></dt>
            <dd><button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
                <?php echo JHtml::_('form.token'); ?>
            </dd>
        </dl>
    </fieldset>
</form>
<div class="clr"></div>
