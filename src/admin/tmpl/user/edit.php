<?php
/**
 * Kunena Component
 *
 * @package           Kunena.Administrator.Template
 * @subpackage        Users
 *
 * @copyright     (C) 2008 - 2022 Kunena Team. All rights reserved.
 * @license           https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link              https://www.kunena.org
 **/
defined('_JEXEC') or die();

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\WebAsset\WebAssetManager;
use Kunena\Forum\Libraries\Date\KunenaDate;
use Kunena\Forum\Libraries\Route\KunenaRoute;
use Kunena\Forum\Libraries\Version\KunenaVersion;

HTMLHelper::_('bootstrap.framework');

/** @var WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('multiselect');

$this->document->addScriptDeclaration(
	' var max_count = ' . (int) $this->config->maxSig . '
jQuery(function($) {
	jQuery(\'#user-signature\').keypress(function (e) {
		var len = jQuery(this).val().length;
		if (len > max_count) {
			e.preventDefault();
		} else if (len <= max_count) {
			var char = max_count - len;

			jQuery(\'#current_count\').val(char);
		}
	});
});
'
);
?>

<div id="kunena" class="container-fluid">
    <div class="row">
        <div id="j-main-container" class="col-md-12" role="main">
            <div class="card card-block bg-faded p-2">
                <form action="<?php echo KunenaRoute::_('administrator/index.php?option=com_kunena&view=users'); ?>"
                      method="post" id="adminForm"
                      name="adminForm">
                    <input type="hidden" name="task" value=""/>
                    <input type="hidden" name="boxchecked" value="1"/>
                    <input type="hidden" name="uid" value="<?php echo $this->user->userid; ?>"/>
					<?php echo HTMLHelper::_('form.token'); ?>
					
					<h1 style="text-transform: capitalize;"><?php echo Text::_('COM_KUNENA_USER_TITLE_EDIT_USERNAME'); ?>
                                    : <?php echo $this->user->username; ?></h1>

                    <article class="data-block">
                        <div class="data-container">
                            <div class="tabbable-panel">
                                <div class="tabbable-line">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab1" type="button" role="tab"
                                                    aria-controls="tab1"
                                                    aria-selected="true"><?php echo Text::_('COM_KUNENA_A_BASIC_SETTINGS'); ?></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2" type="button" role="tab"
                                                    aria-controls="tab2"
                                                    aria-selected="true"><?php echo Text::_('COM_KUNENA_USER_INFO'); ?></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab3-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3" type="button" role="tab"
                                                    aria-controls="tab3"
                                                    aria-selected="true"><?php echo Text::_('COM_KUNENA_MOD_NEW'); ?></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab4-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab4" type="button" role="tab"
                                                    aria-controls="tab4"
                                                    aria-selected="true"><?php echo Text::_('COM_KUNENA_CATEGORY_SUBSCRIPTIONS'); ?></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab5-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab5" type="button" role="tab"
                                                    aria-controls="tab5"
                                                    aria-selected="true"><?php echo Text::_('COM_KUNENA_TOPIC_SUBSCRIPTIONS'); ?></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab6-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab6" type="button" role="tab"
                                                    aria-controls="tab6"
                                                    aria-selected="true"><?php echo Text::_('COM_KUNENA_TRASH_IP'); ?></button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab7-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab7" type="button" role="tab"
                                                    aria-controls="tab7"
                                                    aria-selected="true"><?php echo Text::_('COM_KUNENA_USER_LABEL_FORUM_SETTINGS'); ?></button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                             aria-labelledby="tab1-tab">
                                            <fieldset>
                                                <legend><?php echo Text::_('COM_KUNENA_UAVATAR'); ?></legend>
                                                <div class="kwho-<?php echo $this->user->getType(0, true); ?>">
													<?php echo $this->avatar; ?>
                                                </div>
												<?php
												if ($this->editavatar) : ?>
                                                    <div>
                                                        <label><input type="checkbox" value="1"
                                                                      name="deleteAvatar"/> <?php echo Text::_('COM_KUNENA_DELAV'); ?>
                                                        </label>
                                                    </div>
												<?php endif; ?>
                                            </fieldset>
                                            <fieldset>
                                                <legend><?php echo Text::_('COM_KUNENA_GEN_SIGNATURE'); ?>:</legend>
                                                <div>
														<textarea id="user-signature" class="inputbox form-control"
                                                                  name="signature"
                                                                  cols="4" rows="6"
                                                        ><?php echo $this->escape($this->user->signature); ?></textarea>
                                                </div>
                                                <div>
                                                    <label><input type="checkbox" value="1"
                                                                  name="deleteSig"/> <?php echo Text::_('COM_KUNENA_DELSIG'); ?>
                                                    </label>
                                                </div>
                                                <div>
													<?php echo Text::sprintf(
	'COM_KUNENA_SIGNATURE_LENGTH_COUNTER',
	intval($this->config->maxSig),
	'<input id="current_count" class="col-md-1" readonly="readonly" type="text" name="current_count" value="' . (intval($this->config->maxSig) - Joomla\String\StringHelper::strlen($this->user->signature)) . '" />'
); ?>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="tab-pane fade show" id="tab2" role="tabpanel"
                                             aria-labelledby="tab2-tab">
                                            <fieldset>
                                                <table class="table table-bordered table-striped table-hover">
                                                    <tbody>
                                                    <tr>
                                                        <td class="col-md-3">
                                                            <label for="personalText">
																<?php echo Text::_('COM_KUNENA_MYPROFILE_PERSONALTEXT'); ?>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <input id="personalText" type="text"
                                                                   class="inputbox form-control"
                                                                   maxlength="<?php echo (int) $this->config->maxPersonalText; ?>"
                                                                   name="personalText"
                                                                   value="<?php echo $this->escape($this->user->personalText); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="birthdate">
																<?php echo Text::_('COM_KUNENA_MYPROFILE_BIRTHDATE'); ?>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div id="birthdate">
                                                                <div class="input-append date">
                                                                    <input type="text" name="birthdate"
                                                                           data-date-format="mm/dd/yyyy"
                                                                           value="<?php echo $this->user->birthdate == '1000-01-01' ? '' : KunenaDate::getInstance($this->user->birthdate)->format('m/d/Y'); ?>">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="location">
																<?php echo Text::_('COM_KUNENA_MYPROFILE_LOCATION'); ?>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <input id="location" type="text" name="location"
                                                                   class="inputbox form-control"
                                                                   value="<?php echo $this->escape($this->user->location); ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="gender">
																<?php echo Text::_('COM_KUNENA_MYPROFILE_GENDER'); ?>
                                                            </label>
                                                        </td>
                                                        <td>
															<?php
															// Make the select list for the view type
															$gender[] = HTMLHelper::_('select.option', 0, Text::_('COM_KUNENA_MYPROFILE_GENDER_UNKNOWN'));
															$gender[] = HTMLHelper::_('select.option', 1, Text::_('COM_KUNENA_MYPROFILE_GENDER_MALE'));
															$gender[] = HTMLHelper::_('select.option', 2, Text::_('COM_KUNENA_MYPROFILE_GENDER_FEMALE'));
															// Build the html select list
															echo HTMLHelper::_(
																'select.genericlist',
																$gender,
																'gender',
																'class="inputbox form-control" size="1"',
																'value',
																'text',
																$this->escape($this->user->gender),
																'gender'
															);
															?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="social-site">
																<?php echo Text::_('COM_KUNENA_MYPROFILE_WEBSITE_NAME'); ?>
                                                            </label>
                                                        </td>
                                                        <td>
															<span class="hasTooltip"
                                                                  title="<?php echo Text::_('COM_KUNENA_MYPROFILE_WEBSITE_NAME')
																      . '::' . Text::_('COM_KUNENA_MYPROFILE_WEBSITE_NAME_DESC'); ?>">
																<input id="social-site" type="text" name="websitename"
                                                                       class="inputbox form-control"
                                                                       value="<?php echo $this->escape($this->user->websitename); ?>"/>
															</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label for="social-url">
																<?php echo Text::_('COM_KUNENA_MYPROFILE_WEBSITE_URL'); ?>
                                                            </label>
                                                        </td>
                                                        <td>
															<span class="hasTooltip"
                                                                  title="<?php echo Text::_('COM_KUNENA_MYPROFILE_WEBSITE_URL') . '::' . Text::_('COM_KUNENA_MYPROFILE_WEBSITE_URL_DESC'); ?>">
																<input id="social-url" type="text" name="websiteurl"
                                                                       class="inputbox form-control"
                                                                       value="<?php echo $this->escape($this->user->websiteurl); ?>"/>
															</span>
                                                        </td>
                                                    </tr>

													<?php if ($this->config->social) : ?>
														<?php foreach ($this->social as $key => $social) : ?>
                                                            <tr>
                                                                <td>
                                                                    <label for="social-<?php echo $key; ?>">
																		<?php echo Text::_('COM_KUNENA_MYPROFILE_' . $key); ?>
                                                                    </label>
                                                                </td>
                                                                <td>
																	<?php if ($key != 'qq') : ?>
                                                                    <span class="hasTooltip"
                                                                          title="<?php echo Text::_("COM_KUNENA_MYPROFILE_{$key}")
																		      . '::' . Text::_("COM_KUNENA_MYPROFILE_{$key}_DESC"); ?>">
																	<?php endif; ?>
																		<input id="social-<?php echo $key; ?>"
                                                                               type="text" class="inputbox form-control"
                                                                               name="<?php echo $key ?>"
                                                                               value="<?php echo $this->escape($this->user->$key); ?>"/>
																	</span>
                                                                </td>
                                                            </tr>
														<?php endforeach; ?>
													<?php endif; ?>

                                                    </tbody>
                                                </table>
                                            </fieldset>
                                        </div>

                                        <div class="tab-pane fade show" id="tab3" role="tabpanel"
                                             aria-labelledby="tab3-tab">
                                            <fieldset>
                                                <legend><?php echo Text::_('COM_KUNENA_MODCHANGE'); ?></legend>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td width="20%"><?php echo Text::_('COM_KUNENA_ISMOD'); ?></td>
                                                        <td><?php echo Text::_('COM_KUNENA_MODCATS'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->selectMod; ?></td>
                                                        <td><?php echo $this->modCats; ?></td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </div>

                                        <div class="tab-pane fade show" id="tab4" role="tabpanel"
                                             aria-labelledby="tab4-tab">
                                            <fieldset>
                                                <legend><?php echo Text::_('COM_KUNENA_SUBFOR') . ' ' . $this->escape($this->user->username); ?></legend>
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
														<?php /*
															<th width="1%" class="d-none d-md-table-cell">
																<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll(<?php echo count($this->categories); ?>);" />
															</th>
															*/ ?>
                                                        <th><?php echo Text::_('JGLOBAL_TITLE'); ?></th>
                                                        <th width="1%"><?php echo Text::_('JGRID_HEADING_ID'); ?></th>
                                                    </tr>
                                                    </thead>
													<?php
													if (!empty($this->subsCatsList))
														:
														foreach ($this->subsCatsList as $cat)
															:
															?>
                                                            <tr>
                                                                <td><?php echo $this->escape($cat->name); ?>
                                                                    <small><?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($cat->alias)); ?></small>
                                                                </td>
                                                                <td><?php echo $this->escape($cat->id); ?></td>
                                                            </tr>
														<?php endforeach;
													else:
														?>
                                                        <tr>
                                                            <td><?php echo Text::_('COM_KUNENA_NOCATSUBS'); ?></td>
                                                        </tr>
													<?php endif; ?>
                                                </table>
                                            </fieldset>
                                        </div>

                                        <div class="tab-pane fade show" id="tab5" role="tabpanel"
                                             aria-labelledby="tab5-tab">
                                            <fieldset>
                                                <legend><?php echo Text::_('COM_KUNENA_SUBFOR') . ' ' . $this->escape($this->user->username); ?></legend>
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
														<?php /*
															<th width="1%" class="d-none d-md-table-cell">
																<input type="checkbox" name="checkall-toggle" value="" title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="checkAll(<?php echo count($this->categories); ?>);" />
															</th>
															*/ ?>
                                                        <th><?php echo Text::_('JGLOBAL_TITLE'); ?></th>
                                                        <th width="1%"><?php echo Text::_('JGRID_HEADING_ID'); ?></th>
                                                    </tr>
                                                    </thead>

													<?php
													if ($this->sub)
														:
														foreach ($this->sub as $topic)
															:
															?>
                                                            <tr>
                                                                <td><?php echo $this->escape($topic->subject); ?></td>
                                                                <td><?php echo $this->escape($topic->id); ?></td>
                                                            </tr>
														<?php endforeach;
													else
														:
														?>
                                                        <tr>
                                                            <td><?php echo Text::_('COM_KUNENA_NOSUBS'); ?></td>
                                                        </tr>
													<?php endif; ?>
                                                </table>
                                            </fieldset>
                                        </div>

                                        <div class="tab-pane fade show" id="tab6" role="tabpanel"
                                             aria-labelledby="tab6-tab">
                                            <fieldset>
                                                <legend><?php echo Text::sprintf('COM_KUNENA_IPFOR', $this->escape($this->user->username)); ?></legend>
                                                <table class="table table-striped">
													<?php
													$i          = 0;

													foreach ($this->ipslist as $ip => $list)
														:
														$userlist = [];
														$mescnt = 0;

														foreach ($list as $curuser)
														{
															if ($curuser->userid == $this->user->userid)
															{
																$mescnt += intval($curuser->mescnt);
																continue;
															}

															$userlist[] = $this->escape($curuser->username) . ' (' . $this->escape($curuser->mescnt) . ')';
														}

														$userlist = implode(', ', $userlist);
														?>
                                                        <tr>
                                                            <td width="30"><?php echo ++$i; ?></td>
                                                            <td width="60">
                                                                <strong><?php echo $this->escape($ip); ?></strong>
                                                            </td>
                                                            <td>
                                                                (<?php echo Text::sprintf('COM_KUNENA_IP_OCCURENCES', $mescnt) . (!empty($userlist) ? " " . Text::sprintf('COM_KUNENA_USERIDUSED', $this->escape($userlist)) : ''); ?>
                                                                )
                                                            </td>
                                                        </tr>
													<?php endforeach; ?>
                                                </table>
                                            </fieldset>
                                        </div>

                                        <div class="tab-pane fade show" id="tab7" role="tabpanel"
                                             aria-labelledby="tab7-tab">
                                            <fieldset>
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td width="20%"><?php echo Text::_('COM_KUNENA_PREFOR'); ?></td>
                                                        <td><?php echo $this->selectOrder; ?></td>
                                                    </tr>
													<?php foreach ($this->settings as $field) : ?>
                                                        <tr>
                                                            <td class="col-md-3">
																<?php echo $field->label; ?>
                                                            </td>
                                                            <td>
																<?php echo $field->field; ?>
                                                            </td>
                                                        </tr>
													<?php endforeach ?>
                                                    <tr>
                                                        <td><?php echo Text::_('COM_KUNENA_RANKS'); ?></td>
                                                        <td><?php echo $this->selectRank; ?></td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="pull-right small">
		<?php echo KunenaVersion::getLongVersionHTML(); ?>
    </div>
</div>
