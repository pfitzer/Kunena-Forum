<?php
/**
 * Kunena Component
 *
 * @package       Kunena.Administrator.Template
 * @subpackage    Logs
 *
 * @copyright     Copyright (C) 2008 - 2022 Kunena Team. All rights reserved.
 * @license       https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link          https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Kunena\Forum\Libraries\Date\KunenaDate;
use Kunena\Forum\Libraries\Forum\Category\KunenaCategoryHelper;
use Kunena\Forum\Libraries\Forum\Topic\KunenaTopicHelper;
use Kunena\Forum\Libraries\Html\KunenaParser;
use Kunena\Forum\Libraries\Route\KunenaRoute;
use Kunena\Forum\Libraries\User\KunenaUserHelper;
use Kunena\Forum\Libraries\Version\KunenaVersion;

$filterItem = $this->escape($this->state->get('item.id'));
?>

<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order !== '<?php echo $this->listOrdering; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>

<div id="kunena" class="container-fluid">
    <div class="row">
        <div id="j-main-container" class="col-md-12" role="main">
            <div class="card card-block bg-faded p-2">
                <form action="<?php echo KunenaRoute::_('administrator/index.php?option=com_kunena&view=logs'); ?>"
                      method="post" name="adminForm"
                      id="adminForm">
                    <input type="hidden" name="task" value=""/>
                    <input type="hidden" name="boxchecked" value="1"/>
                    <input type="hidden" name="filter_order" value="<?php echo $this->list->Ordering; ?>"/>
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->list->Direction; ?>"/>
					<?php echo HTMLHelper::_('form.token'); ?>

                    <div id="filter-bar" class="btn-toolbar">
                        <div class="btn-group pull-left">
							<?php echo HTMLHelper::calendar($this->filter->TimeStart, 'filter_time_start', 'filter_time_start', '%Y-%m-%d', ['class' => 'filter btn-wrapper', 'placeholder' => Text::_('COM_KUNENA_LOG_CALENDAR_PLACEHOLDER_START_DATE')]); ?>
							<?php echo HTMLHelper::calendar($this->filter->TimeStop, 'filter_time_stop', 'filter_time_stop', '%Y-%m-%d', ['class' => 'filter wrapper', 'placeholder' => Text::_('COM_KUNENA_LOG_CALENDAR_PLACEHOLDER_END_DATE')]); ?>
                        </div>
                        <div class="btn-group pull-left">
                            <button class="btn btn-outline-primary tip" type="submit"
                                    title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT'); ?>"><i
                                        class="icon-search"></i> <?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>
                            </button>
                            <button class="btn btn-outline-primary tip" type="button"
                                    title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERRESET'); ?>"
                                    onclick="jQuery('.filter').val('');jQuery('#adminForm').submit();"><i
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
                                    onchange="Joomla.orderTable()">
                                <option value=""><?php echo Text::_('JFIELD_ORDERING_DESC'); ?></option>
								<?php echo HTMLHelper::_('select.options', $this->sortDirectionFields, 'value', 'text', $this->list->Direction); ?>
                            </select>
                        </div>
                        <div class="btn-group pull-right">
                            <label for="sortTable"
                                   class="element-invisible"><?php echo Text::_('JGLOBAL_SORT_BY'); ?></label>
                            <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                                <option value=""><?php echo Text::_('JGLOBAL_SORT_BY'); ?></option>
								<?php echo HTMLHelper::_('select.options', $this->sortFields, 'value', 'text', $this->list->Ordering); ?>
                            </select>
                        </div>
                        <div class="btn-group pull-right">
                            <label for="sortTable" class="element-invisible"><?php echo 'Filter users by:'; ?></label>
                            <select name="filter_usertypes" id="filter_usertypes" class="input-medium filter"
                                    onchange="Joomla.orderTable()">
                                <option value=""><?php echo 'All'; ?></option>
								<?php echo HTMLHelper::_('select.options', $this->filter->UserFields, 'value', 'text', $this->filter->Usertypes); ?>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <table class="table table-striped" id="logList">
                        <thead>
                        <tr>
                            <th class="nowrap center" width="1%">
								<?php echo !$this->group ? HTMLHelper::_('grid.sort', 'JGRID_HEADING_ID', 'id', $this->list->Direction, $this->list->Ordering) : 'Count'; ?>
                            </th>
                            <th class="nowrap center" width="1%" style="width: 130px;">
								<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_LOG_TIME_SORT_LABEL', 'time', $this->list->Direction, $this->list->Ordering); ?>
                            </th>
                            <th class="nowrap" width="1%">
								<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_LOG_TYPE_SORT_LABEL', 'type', $this->list->Direction, $this->list->Ordering); ?>
								<?php echo $this->getGroupCheckbox('type'); ?>
                            </th>
                            <th class="nowrap center">
                                Operation
								<?php echo $this->getGroupCheckbox('operation'); ?>
                            </th>
                            <th class="nowrap">
								<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_LOG_USER_SORT_LABEL', 'user', $this->list->Direction, $this->list->Ordering); ?>
								<?php echo $this->getGroupCheckbox('user'); ?>
                            </th>
                            <th class="nowrap">
								<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_LOG_CATEGORY_SORT_LABEL', 'category', $this->list->Direction, $this->list->Ordering); ?>
								<?php echo $this->getGroupCheckbox('category'); ?>
                            </th>
                            <th class="nowrap">
								<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_LOG_TOPIC_SORT_LABEL', 'topic', $this->list->Direction, $this->list->Ordering); ?>
								<?php echo $this->getGroupCheckbox('topic'); ?>
                            </th>
                            <th class="nowrap">
								<?php echo HTMLHelper::_('grid.sort', 'COM_KUNENA_LOG_TARGET_USER_SORT_LABEL', 'target_user', $this->list->Direction, $this->list->Ordering); ?>
								<?php echo $this->getGroupCheckbox('target_user'); ?>
                            </th>
                            <th class="nowrap center">
                                IP
								<?php echo $this->getGroupCheckbox('ip'); ?>
                            </th>
							<?php if (!$this->group)
								:
								?>
                                <th class="nowrap center">
									<?php echo Text::_('COM_KUNENA_LOG_MANAGER') ?>
                                </th>
							<?php endif; ?>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td>
                            </td>
                            <td>
                                <label for="filterType" class="element-invisible"><?php echo 'Type'; ?></label>
                                <select name="filterType" id="filterType" class="select-filter filter form-control"
                                        onchange="Joomla.orderTable()">
                                    <option value=""><?php echo Text::_('COM_KUNENA_FIELD_LABEL_ALL'); ?></option>
									<?php echo HTMLHelper::_('select.options', $this->filter->TypeFields, 'value', 'text', $this->filter->Type); ?>
                                </select>
                            </td>
                            <td>
                                <label for="filter_operation" class="element-invisible"><?php echo 'Type'; ?></label>
                                <select name="filter_operation" id="filter_operation" class="filter form-control"
                                        onchange="Joomla.orderTable()">
                                    <option value=""><?php echo Text::_('COM_KUNENA_FIELD_LABEL_ALL'); ?></option>
									<?php echo HTMLHelper::_('select.options', $this->filter->OperationFields, 'value', 'text', $this->filter->Operation); ?>
                                </select>
                            </td>
                            <td>
                                <label for="filter_user"
                                       class="element-invisible"><?php echo Text::_('COM_KUNENA_LOG_USER_FILTER_LABEL'); ?></label>
                                <input class="input-block-level input-filter filter form-control" type="text"
                                       name="filter_user"
                                       id="filter_user"
                                       placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
                                       value="<?php echo $this->filter->User; ?>"
                                       title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
                            </td>
                            <td>
                                <label for="filter_category"
                                       class="element-invisible"><?php echo Text::_('COM_KUNENA_LOG_CATEGORY_FILTER_LABEL'); ?></label>
                                <input class="input-block-level input-filter filter form-control" type="text"
                                       name="filter_category"
                                       id="filter_category"
                                       placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
                                       value="<?php echo $this->filter->Category; ?>"
                                       title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
                            </td>
                            <td>
                                <label for="filter_topic"
                                       class="element-invisible"><?php echo Text::_('COM_KUNENA_LOG_TOPIC_FILTER_LABEL'); ?></label>
                                <input class="input-block-level input-filter filter form-control" type="text"
                                       name="filter_topic"
                                       id="filter_topic"
                                       placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
                                       value="<?php echo $this->filter->Topic; ?>"
                                       title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
                            </td>
                            <td>
                                <label for="filter_target_user"
                                       class="element-invisible"><?php echo Text::_('COM_KUNENA_LOG_TARGET_USER_FILTER_LABEL'); ?></label>
                                <input class="input-block-level input-filter filter form-control" type="text"
                                       name="filter_target_user"
                                       id="filter_target_user"
                                       placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
                                       value="<?php echo $this->filter->TargetUser; ?>"
                                       title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
                            </td>
                            <td>
                                <label for="filter_ip"
                                       class="element-invisible"><?php echo Text::_('COM_KUNENA_LOG_IP_FILTER_LABEL'); ?></label>
                                <input class="input-block-level input-filter filter form-control" type="text"
                                       name="filter_ip"
                                       id="filter_ip"
                                       placeholder="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"
                                       value="<?php echo $this->filter->Ip; ?>"
                                       title="<?php echo Text::_('COM_KUNENA_SYS_BUTTON_FILTERSUBMIT') ?>"/>
                            </td>
							<?php if (!$this->group)
								:
								?>
                                <td>
                                </td>
							<?php endif; ?>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <td colspan="10">
								<?php echo $this->pagination->getListFooter(); ?>
                            </td>
                        </tr>
                        </tfoot>
                        <tbody>
						<?php
						$i                = 0;

						if ($this->pagination->total > 0)
							:
							foreach ($this->items as $item)
								:
								$date = new KunenaDate($item->time);
								$user     = KunenaUserHelper::get($item->user_id);
								$category = KunenaCategoryHelper::get($item->category_id);
								$topic    = KunenaTopicHelper::get($item->topic_id);
								$target   = KunenaUserHelper::get($item->target_user);

								// TODO : move this part of javascript outside of foreach
								$this->document->addScriptDeclaration("jQuery( document ).ready(function() {
										jQuery('#link_sel_all" . $item->id . "').click(function() {
											jQuery('#report_final" . $item->id . "').select();
											try {
												var successful = document.execCommand('copy');
												var msg = successful ? 'successful' : 'unsuccessful';
												console.log('Copying text command was ' + msg);
											}
											catch (err)
											{
												console.log('Oops, unable to copy');
											}
										});
									});");
								?>
                                <tr>
                                    <td class="center">
										<?php echo !$this->group ? $this->escape($item->id) : (int) $item->count; ?>
                                    </td>
                                    <td class="center">
										<?php echo $date->toSql(); ?>
                                    </td>
                                    <td class="center">
										<?php echo !$this->group || isset($this->group['type']) ? $this->escape($this->getType((int) $item->type)) : ''; ?>
                                    </td>
                                    <td class="center">
										<?php echo !$this->group || isset($this->group['operation']) ? Text::_("COM_KUNENA_{$item->operation}") : ''; ?>
                                    </td>
                                    <td>
										<?php echo !$this->group || isset($this->group['user']) ? ($user->userid ? $this->escape($user->username) . ' <small>(' . $this->escape($item->user_id) . ')</small>' . '<br />' . $this->escape($user->name) : '') : ''; ?>
                                    </td>
                                    <td>
										<?php echo !$this->group || isset($this->group['category']) ? ($category->exists() ? $category->displayField('name') . ' <small>(' . $this->escape($item->category_id) . ')</small>' : '') : ''; ?>
                                    </td>
                                    <td>
										<?php echo !$this->group || isset($this->group['topic']) ? ($topic->exists() ? $topic->displayField('subject') . ' <small>(' . $this->escape($item->topic_id) . ')</small>' : '') : ''; ?>
                                    </td>
                                    <td>
										<?php echo !$this->group || isset($this->group['target_user']) ? ($target->userid ? $this->escape($target->username) . ' <small>(' . $this->escape($item->target_user) . ')</small>' . '<br />' . $this->escape($target->name) : '') : ''; ?>
                                    </td>
                                    <td class="center">
										<?php echo !$this->group || isset($this->group['ip']) ? $this->escape($item->ip) : ''; ?>
                                    </td>
									<?php if (!$this->group) : ?>
                                        <td>
                                            <a href="#kerror<?php echo $item->id; ?>_form" role="button"
                                               class="btn openmodal"
                                               data-bs-toggle="modal"
                                               data-bs-target="#kerror<?php echo $item->id; ?>_form"
                                               rel="nofollow">
												<?php if ($this->escape($this->getType($item->type)) != 'ACT') : ?>
                                                    <span class="icon-warning" aria-hidden="true"></span>
												<?php else: ?>
                                                    <span class="icon-edit" aria-hidden="true"></span>
												<?php endif; ?>
												<?php echo !$this->group || isset($this->group['type']) ? $this->escape($this->getType($item->type)) : ''; ?>
                                            </a>
                                        </td>
									<?php endif; ?>
                                </tr>

                                <div class="modal fade" id="kerror<?php echo $item->id; ?>_form" tabindex="-1"
                                     role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLabel"><?php if ($this->escape($this->getType($item->type)) != 'ACT') : ?>
                                                        <span class="icon-warning" aria-hidden="true"></span>
													<?php else: ?>
                                                        <span class="icon-edit" aria-hidden="true"></span>
													<?php endif; ?>
                                                    Kunena <?php echo !$this->group || isset($this->group['type']) ? $this->escape($this->getType($item->type)) : ''; ?>

                                                    ID:<?php echo $item->id; ?></h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div>
													<textarea style="margin-top: -3000px"
                                                              id="report_final<?php echo $item->id; ?>"
                                                              for="report_final<?php echo $item->id; ?>"><?php echo KunenaParser::plainBBCode((string) $item->data); ?></textarea>
                                                    <pre><?php echo json_encode(json_decode($item->data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></pre>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="#" id="link_sel_all<?php echo $item->id; ?>"
                                                   name="link_sel_all<?php echo $item->id; ?>" type="button"
                                                   class="btn btn-small btn-outline-primary"><i
                                                            class="icon icon-signup"></i><?php echo Text::_('COM_KUNENA_REPORT_SELECT_ALL'); ?>
                                                </a>
                                                <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal"><?php echo Text::_('COM_KUNENA_EDITOR_MODAL_CLOSE_LABEL') ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php
								$i++;
							endforeach;
						else:
							?>
                            <tr>
                                <td colspan="10">
                                    <div class="card card-block bg-faded p-2 center filter-state">
								<span><?php echo Text::_('COM_KUNENA_FILTERACTIVE'); ?>
									<?php
									if ($this->filter->Active)
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
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="pull-right small">
		<?php echo KunenaVersion::getLongVersionHTML(); ?>
    </div>
</div>
