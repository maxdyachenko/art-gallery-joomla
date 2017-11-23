<?php

// No direct access to this file
defined('_JEXEC') or die;


?>
<form action="index.php?option=com_artgallery&view=gallerys" method="post" id="adminForm" name="adminForm">


    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="3%"><?php echo "ID"; ?></th>
            <th width="2%">
                <!--checkbox-->
            </th>
            <th width="50%">
                <?php echo JText::_('COM_ARTGALLERY_USER_NAME'); ?>
            </th>
            <th width="30%">
                <?php echo JText::_('COM_ARTGALLERY_USER_EMAIL'); ?>
            </th>
            <th width="15%">
                <?php echo JText::_('COM_ARTGALLERY_BAN_USER'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->items as $i =>$item):
            $link = JRoute::_('index.php?option=com_artgallery&view=gallery&id=' . $item->id);
            ?>
            <tr>
                <td>
                    <?php echo $i; ?>
                </td>
                <td>
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                    <a href="<?php echo $link; ?>">
                        <?php echo $item->name; ?>
                    </a>
                </td>
                <td align="center">
                    <?php echo $item->email; ?>
                </td>
                <td align="center">
                    <!--                        insert here ban button-->
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

