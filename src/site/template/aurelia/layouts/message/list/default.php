<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Aurelia
 * @subpackage      Layout.Message
 *
 * @copyright       Copyright (C) 2008 - 2022 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/

namespace Kunena\Forum\Site;

\defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Kunena\Forum\Libraries\Icons\KunenaIcons;
use Kunena\Forum\Libraries\Route\KunenaRoute;
use Kunena\Forum\Libraries\Template\KunenaTemplate;

$view    = Factory::getApplication()->input->getWord('view');
?>

<div class="row">
    <div class="col-md-12">
        <div class="float-start">
            <h1>
				<?php echo $this->escape($this->headerText); ?>
                <small class="d-none d-sm-block">
                    (<?php echo Text::sprintf($this->messagemore, $this->formatLargeNumber($this->pagination->total)); ?>
                    )
                </small>

				<?php // ToDo:: <span class="badge badge-success"> <?php echo $this->topics->count->unread; ?/></span>
				?>
            </h1>
        </div>

		<?php if ($view != 'user')
			:
			?>
            <div class="filter-sel float-end">
                <h2 class="filter-time float-end" id="filter-time"></h2>
                <form action="<?php echo $this->escape(Uri::getInstance()->toString()); ?>"
                      id="timeselect" name="timeselect"
                      method="post" target="_self" class="form-inline d-none d-sm-block">
                    <?php $this->displayTimeFilter('sel'); ?>
                    <?php echo HTMLHelper::_('form.token'); ?>
                </form>
            </div>
		<?php endif; ?>
    </div>
</div>

<div class="float-end">
	<?php echo $this->subLayout('Widget/Search')
		->set('catid', 'all')
		->setLayout('topic'); ?>
</div>

<div class="float-start">
	<?php echo $this->subLayout('Widget/Pagination/List')
		->set('pagination', $this->pagination->setDisplayedPages(4))
		->set('display', true); ?>
</div>

<form action="<?php echo KunenaRoute::_('index.php?option=com_kunena&view=topics'); ?>" method="post"
      name="ktopicsform" id="ktopicsform">
	<?php echo HTMLHelper::_('form.token'); ?>

    <table class="table<?php echo KunenaTemplate::getInstance()->borderless(); ?>">
        <thead>
		<?php if (empty($this->messages)): ?>
            <tr>
                <th scope="row">&nbsp;</th>
            </tr>
		<?php else : ?>
            <th scope="col" class="center d-none d-md-table-cell">
                <a id="forumtop"> </a>
                <a href="#forumbottom" rel="nofollow">
					<?php echo KunenaIcons::arrowdown(); ?>
                </a>
            </th>
            <th scope="col" class="d-none d-md-table-cell"><?php echo Text::_('COM_KUNENA_GEN_SUBJECT'); ?></th>
            <th scope="col" class="d-none d-md-table-cell"><?php echo Text::_('COM_KUNENA_GEN_REPLIES'); ?>
                / <?php echo Text::_('COM_KUNENA_GEN_HITS'); ?></th>
            <th scope="col" class="d-none d-md-table-cell"><?php echo Text::_('COM_KUNENA_GEN_LAST_POST'); ?></th>

			<?php if (!empty($this->actions)) : ?>
                <th scope="col" class="center"><input class="kcheckall" type="checkbox" name="toggle" value=""/></th>
			<?php endif; ?>
		<?php endif; ?>
        </thead>

        <tfoot>
		<?php if (!empty($this->messages)) : ?>
            <tr>
                <th scope="col" class="center d-none d-md-table-cell">
                    <a id="forumbottom"> </a>
                    <a href="#forumtop" rel="nofollow">
                        <span class="dropdown-divider"></span>
						<?php echo KunenaIcons::arrowup(); ?>
                    </a>
                </th>
				<?php if (!empty($this->actions)) : ?>
                    <th scope="col" class="d-none d-md-table-cell">
                        <div class="form-group">
                            <div class="input-group" role="group">
								<?php if (!empty($this->moreUri))
								{
									echo HTMLHelper::_('kunenaforum.link', $this->moreUri, Text::_('COM_KUNENA_MORE'), null, 'btn btn-outline-primary float-start', 'nofollow');
								} ?>
								<?php
								if (!empty($this->actions))
									:
									?>
									<?php echo HTMLHelper::_('select.genericlist', $this->actions, 'task', 'class="form-select kchecktask" ', 'value', 'text', 0, 'kchecktask'); ?>
									<?php
									if (isset($this->actions['move']))
										:
										$options = [HTMLHelper::_('select.option', '0', Text::_('COM_KUNENA_BULK_CHOOSE_DESTINATION'))];
										echo HTMLHelper::_('kunenaforum.categorylist', 'target', 0, $options, [], 'class="form-select fbs" disabled="disabled"', 'value', 'text', 0, 'kchecktarget');
									endif; ?>
									<input type="submit" name="kcheckgo" class="btn btn-outline-primary border"
											value="<?php echo Text::_('COM_KUNENA_GO') ?>"/>
								<?php endif; ?>
                            </div>
                        </div>
                    </th>
				<?php endif; ?>
            </tr>
		<?php endif; ?>
        </tfoot>

        <tbody class="message-list">
		<?php
		foreach ($this->messages as $i => $message)
		{
			echo $this->subLayout('Message/Row')
				->set('message', $message)
				->set('position', $i)
				->set('checkbox', !empty($this->actions));
		}
		?>
        </tbody>
    </table>
</form>

<div class="float-start">
	<?php echo $this->subLayout('Widget/Pagination/List')
		->set('pagination', $this->pagination->setDisplayedPages(4))
		->set('display', true); ?>
</div>

<?php if ($view != 'user')
	:
	?>
    <form action="<?php echo $this->escape(Uri::getInstance()->toString()); ?>" id="timeselect"
          name="timeselect"
          method="post" target="_self" class="timefilter float-end">
		<?php $this->displayTimeFilter('sel'); ?>
    </form>
<?php endif; ?>

<div class="clearfix"></div>
