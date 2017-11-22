<?php

// No direct access to this file
defined('_JEXEC') or die;


?>
<form action="index.php?option=com_artgallery&view=users" method="post" id="adminForm" name="adminForm">

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="3%"><?php echo "ID"; ?></th>
            <th width="2%">
                <!--checkbox-->
            </th>
            <th width="70%">
                <?php echo JText::_('COM_ARTGALLERY_USER_NAME'); ?>
            </th>
            <th width="15%">
                <?php echo JText::_('COM_ARTGALLERY_BAN_USER'); ?>
            </th>
            <th width="10%">
                <?php echo JText::_('COM_ARTGALLERY_USER_ID'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($this->users as $i =>$user):
                $link = JRoute::_('index.php?option=com_artgallery&view=gallerys&id=' . $user->id);
                ?>
                <tr>
                    <td>
                        <?php echo $i; ?>
                    </td>
                    <td>
                        <?php echo JHtml::_('grid.id', $i, $user->id); ?>
                    </td>
                    <td>
                        <a href="<?php echo $link; ?>">
                            <?php echo $user->username; ?>
                        </a>
                    </td>
                    <td align="center">
<!--                        insert here ban button-->
                    </td>
                    <td align="center">
                        <?php echo $user->id; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

