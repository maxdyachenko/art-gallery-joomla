<?php
/**
 * EBR - Easybook Reloaded for Joomla! 3.x
 * License: GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * Author: Viktor Vogel
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
defined('_JEXEC') OR die('Restricted access');

class EasybookReloadedModelBadwords extends JModelLegacy
{
	protected $data;
	protected $total;
	protected $pagination;

	public function __construct()
	{
		parent::__construct();

		$app = JFactory::getApplication();

		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest('easybookreloaded.limitstart', 'limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$search = $app->getUserStateFromRequest('easybookreloaded.filter.search', 'filter_search', null);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter.search', $search);
	}

	public function getData()
	{
		if(empty($this->data))
		{
			$query = $this->buildQuery();
			$this->data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->data;
	}

	private function buildQuery()
	{
		$query = $this->_db->getQuery(true);

		$query->select('*');
		$query->from('#__easybook_badwords AS a');

		$search = $this->getState('filter.search');

		if(!empty($search))
		{
			$search = $this->_db->quote('%'.$this->_db->escape($search, true).'%');
			$query->where('(a.word LIKE '.$search.')');
		}

		$query->order($this->_db->escape('word ASC'));

		return $query;
	}

	public function getPagination()
	{
		if(empty($this->pagination))
		{
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->pagination;
	}

	public function getTotal()
	{
		if(empty($this->total))
		{
			$query = $this->buildQuery();
			$this->total = $this->_getListCount($query);
		}

		return $this->total;
	}
}
