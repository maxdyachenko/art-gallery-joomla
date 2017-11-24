<?php

// No direct access to this file
defined('_JEXEC') or die;

$listOrder     = $this->escape($this->state->get('list.ordering'));
$listDirn    = $this->escape($this->state->get('list.direction'));
?>
<form action="index.php?option=com_artgallery&view=users" method="post" id="adminForm" name="adminForm">
    <div class="row-fluid">
        <div class="span6">
            <?php echo JText::_('COM_HELLOWORLD_HELLOWORLDS_FILTER'); ?>
            <?php
            echo JLayoutHelper::render(
                'joomla.searchtools.default',
                array('view' => $this)
            );
            ?>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="3%"><?php echo "#"; ?></th>
            <th width="2%">
                <!--checkbox-->
            </th>
            <th width="50%">
                <?php echo JHtml::_('grid.sort', 'COM_ARTGALLERY_NAME', 'username', $listDirn, $listOrder); ?>
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
            <?php foreach ($this->users as $i =>$user):
                $link = JRoute::_('index.php?option=com_artgallery&view=gallerys&id=' . $user->id);
                ?>
                <tr>
                    <td>
                        <?php echo $i+1; ?>
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
                        <?php echo $user->email; ?>
                    </td>
                    <td align="center">
                        <?php echo JHtml::_('jgrid.published', $user->block, $i, 'users.', true); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
    </table>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

