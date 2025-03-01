<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator.Template
 * @subpackage      Trash
 *
 * @copyright       Copyright (C) 2008 - 2022 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\WebAsset\WebAssetManager;
use Kunena\Forum\Libraries\Version\KunenaVersion;
use Kunena\Forum\Libraries\Route\KunenaRoute;

/** @var WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('multiselect');
?>

<div id="kunena" class="container-fluid">
	<div class="row">
		<div id="j-main-container" class="col-md-12" role="main">
			<div class="card card-block bg-faded p-2">
				<form action="<?php echo KunenaRoute::_('administrator/index.php?option=com_kunena&view=trash') ?>"
					  method="post" id="adminForm"
					  name="adminForm">
					<input type="hidden" name="type" value="<?php echo $this->escape($this->state->get('layout')) ?>"/>
					<input type="hidden" name="layout"
						   value="<?php echo $this->escape($this->state->get('layout')) ?>"/>
					<input type="hidden" name="filter_order"
						   value="<?php echo intval($this->state->get('list.ordering')) ?>"/>
					<input type="hidden" name="filter_order_Dir"
						   value="<?php echo $this->escape($this->state->get('list.direction')) ?>"/>
					<input type="hidden" name="task" value=""/>
					<input type="hidden" name="boxchecked" value="0"/>
					<?php echo HTMLHelper::_('form.token'); ?>

					<fieldset>
						<legend><?php echo Text::_('COM_KUNENA_TRASH_VIEW') . ' ' . Text::_('COM_KUNENA_TRASH_MESSAGES') ?>
							<span
									class="pull-right"><?php echo $this->viewOptionsList; ?></span></legend>

						<div id="filter-bar" class="btn-toolbar">
							<div class="filter-search btn-group pull-left">
								<label for="filter_search"
									   class="element-invisible"><?php echo Text::_('COM_KUNENA_FIELD_LABEL_SEARCHIN') ?></label>
								<input type="text" name="filter_search" id="filter_search" class="filter form-control"
									   placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
									   value="<?php echo $this->filterSearch; ?>"
									   title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
							</div>
							<div class="btn-group pull-left">
								<button class="btn btn-outline-primary tip" type="submit"
										title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT'); ?>"><i
											class="icon-search"></i> <?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT'); ?>
								</button>
								<button class="btn btn-outline-danger tip" type="button"
										title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERRESET'); ?>"
										onclick="document.id('filter_search').value='';this.form.submit();"><i
											class="icon-remove"></i> <?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERRESET'); ?>
								</button>
							</div>
							<div class="btn-group pull-right d-none d-md-block">
								<label for="limit"
									   class="element-invisible"><?php echo Text::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
								<?php echo $this->pagination->getLimitBox(); ?>
							</div>
							<div class="btn-group pull-right d-none d-md-block">
								<label for="directionTable"
									   class="element-invisible"><?php echo Text::_('JFIELD_ORDERING_DESC'); ?></label>
								<select name="directionTable" id="directionTable" class="input-medium"
										onchange="orderTable()">
									<option value=""><?php echo Text::_('JFIELD_ORDERING_DESC'); ?></option>
									<?php echo HTMLHelper::_('select.options', $this->sortDirectionFields, 'value', 'text', $this->listDirection); ?>
								</select>
							</div>
							<div class="btn-group pull-right">
								<label for="sortTable"
									   class="element-invisible"><?php echo Text::_('JGLOBAL_SORT_BY'); ?></label>
								<select name="sortTable" id="sortTable" class="input-medium"
										onchange="orderTable()">
									<option value=""><?php echo Text::_('JGLOBAL_SORT_BY'); ?></option>
									<?php echo HTMLHelper::_('select.options', $this->sortFields, 'value', 'text', $this->listOrdering); ?>
								</select>
							</div>
							<div class="clearfix"></div>
						</div>

						<table class="table table-striped">
							<thead>
							<tr>
								<th width="1%" class="nowrap center">
									<input type="checkbox" name="checkall-toggle" value=""
										   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>"
										   onclick="Joomla.checkAll(this);"/>
								</th>
								<th width="1%" class="nowrap">
									<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_TRASH_ID', 'id', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
								</th>
								<th>
									<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_TRASH_TITLE', 'title', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
								</th>
								<th width="15%" class="nowrap">
									<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_MENU_TOPIC', 'topic', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
								</th>
								<th>
									<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_TRASH_CATEGORY', 'category', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
								</th>
								<th width="10%" class="nowrap">
									<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_TRASH_AUTHOR', 'author', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
								</th>
								<th width="15%" class="nowrap">
									<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_TRASH_IP', 'ip', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
								</th>
								<th width="10%" class="nowrap">
									<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_TRASH_DATE', 'time', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
								</th>
							</tr>
							<tr>
								<td class="d-none d-md-table-cell">
								</td>
								<td class="d-none d-md-table-cell">
								</td>
								<td class="d-none d-md-table-cell">
									<label for="filterTitle"
										   class="element-invisible"><?php echo Text::_('COM_KUNENA_FIELD_LABEL_SEARCHIN'); ?></label>
									<input class="input-block-level input-filter form-control" type="text"
										   name="filterTitle"
										   id="filterTitle"
										   placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
										   value="<?php echo $this->filterTitle; ?>"
										   title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
								</td>
								<td class="d-none d-md-table-cell">
									<label for="filter_topic"
										   class="element-invisible"><?php echo Text::_('COM_KUNENA_FIELD_LABEL_SEARCHIN'); ?></label>
									<input class="input-block-level input-filter form-control" type="text"
										   name="filter_topic"
										   id="filter_topic"
										   placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT'); ?>"
										   value="<?php echo $this->filterTopic; ?>"
										   title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT'); ?>"/>
								</td>
								<td class="d-none d-md-table-cell">
									<label for="filter_category"
										   class="element-invisible"><?php echo Text::_('COM_KUNENA_FIELD_LABEL_SEARCHIN'); ?></label>
									<input class="input-block-level input-filter form-control" type="text"
										   name="filter_category"
										   id="filter_category"
										   placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
										   value="<?php echo $this->filterCategory; ?>"
										   title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
								</td>
								<td class="nowrap">
									<label for="filter_ip"
										   class="element-invisible"><?php echo Text::_('COM_KUNENA_FIELD_LABEL_SEARCHIN'); ?></label>
									<input class="input-block-level input-filter form-control" type="text"
										   name="filter_ip"
										   id="filter_ip"
										   placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
										   value="<?php echo $this->filterIp; ?>"
										   title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
								</td>
								<td class="nowrap">
									<label for="filter_author"
										   class="element-invisible"><?php echo Text::_('COM_KUNENA_FIELD_LABEL_SEARCHIN'); ?></label>
									<input class="input-block-level input-filter form-control" type="text"
										   name="filter_author"
										   id="filter_author"
										   placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
										   value="<?php echo $this->filterAuthor; ?>"
										   title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
								</td>
								<td class="nowrap">
									<?php /*
											<label for="filter_time" class="element-invisible"><?php echo Text::_('COM_KUNENA_FIELD_LABEL_SEARCHIN'); ?></label>
											<input class="input-block-level input-filter form-control" type="text" name="filter_time" id="filter_time" placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>" value="<?php echo $this->filterDate; ?>" title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>" />
											*/ ?>
								</td>
								<td class="nowrap">
								</td>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<td colspan="8">
									<?php echo $this->pagination->getListFooter(); ?>
								</td>
							</tr>
							</tfoot>
							<tbody>
							<?php
							$i      = 0;
							$itemid = KunenaRoute::fixMissingItemID();

							if ($this->pagination->total > 0)
								:
								foreach ($this->trashInternalItems as $id => $row)
									:
									?>
									<tr>
										<td><?php echo HTMLHelper::_('grid.id', $i++, intval($row->id)) ?></td>
										<td><?php echo intval($row->id); ?></td>
										<td>
											<a href="<?php echo KunenaRoute::_('index.php?option=com_kunena&view=topic&catid=' . $row->getTopic()->category_id . '&id=' . $row->getTopic()->id . '&mesid=' . $row->id . '&Itemid=' . $itemid . '#' . $row->id); ?>"
											   target="_blank"><?php echo $this->escape($row->subject); ?></a></td>
										<td>
											<a href="<?php echo KunenaRoute::_('index.php?option=com_kunena&view=topic&catid=' . $row->getTopic()->category_id . '&id=' . $row->getTopic()->id . '&Itemid=' . $itemid); ?>"
											   target="_blank"><?php echo $this->escape($row->getTopic()->subject); ?></a>
										</td>
										<td><?php echo $this->escape($row->getCategory()->name); ?></td>
										<td><?php echo $this->escape($row->getAuthor()->getName()); ?></td>
										<td><?php echo $this->escape($row->ip); ?></td>
										<td><?php echo Factory::getDate($row->time)->format('%Y-%m-%d %H:%M:%S', $row->time); ?></td>
									</tr>
								<?php
								endforeach;
							else:
								?>
								<tr>
									<td colspan="10">
										<div class="card card-block bg-faded p-2 center filter-state">
												<span><?php echo Text::_('COM_KUNENA_FILTERACTIVE'); ?>
													<?php
													if ($this->filterActive || $this->pagination->total > 0)
														:
														?>
														<button class="btn btn-outline-primary" type="button"
																onclick="document.getElements('.filter').set('value', '');this.form.submit();"><?php echo Text::_('COM_KUNENA_FIELD_LABEL_FILTERCLEAR'); ?></button>
													<?php endif; ?>
												</span>
										</div>
									</td>
								</tr>
							<?php endif; ?>
							</tbody>
						</table>
					</fieldset>
				</form>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="pull-right small">
		<?php echo KunenaVersion::getLongVersionHTML(); ?>
	</div>
</div>
