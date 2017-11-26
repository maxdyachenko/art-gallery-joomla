<?php
/**
 * EBR - Easybook Reloaded for Joomla! 3.x
 * License: GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Author: Viktor Vogel <admin@kubik-rubik.de>
 * Project page: https://joomla-extensions.kubik-rubik.de/ebr-easybook-reloaded
 *
 * @license GNU/GPL
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');

class EasybookReloadedViewEasybookReloaded extends JViewLegacy
{
	function display($tpl = null)
	{
		// Get the data from the model
		$items = $this->get('Data');
		$gb_data = $this->get('GBData');

		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$document->link = JRoute::_('index.php?option=com_easybookreloaded&view=easybookreloaded&gbid='.$gb_data->id);
		JFactory::getApplication()->input->set('limit', $app->get('feed_limit'));

		// Get the Item ID of the menu entry
		require_once(JPATH_SITE.'/components/com_easybookreloaded/helpers/route.php');
		$item_id = EasybookReloadedHelperRoute::getItemId($gb_data->id);

		foreach($items as $item)
		{
			if(!empty($item->gbtitle))
			{
				$title = html_entity_decode($this->escape($item->gbtitle.' - '.$item->gbname));
			}
			else
			{
				$title = html_entity_decode($this->escape($item->gbname));
			}

			// Create correct link to the entry
			$limit = EasybookReloadedHelperRoute::getLimitstart($item->id);

			$link_raw = 'index.php?option=com_easybookreloaded&view=easybookreloaded';
			$link_raw .= '&gbid='.$gb_data->id;
			$link_raw .= '&Itemid='.$item_id;

			if(!empty($limit))
			{
				$link_raw .= '&limitstart='.$limit;
			}

			$link_raw .= '#gbentry_'.$item->id;
			$link = JRoute::_($link_raw);
			$description = $item->gbtext;
			$date = ($item->gbdate ? date('r', strtotime($item->gbdate)) : '');

			// Add prepared entry to the feed
			$feeditem = new JFeedItem();
			$feeditem->title = $title;
			$feeditem->link = $link;
			$feeditem->description = $description;
			$feeditem->date = $date;
			$feeditem->category = 'Guestbook';

			// Add entry to RSS array
			$document->addItem($feeditem);
		}
	}
}
