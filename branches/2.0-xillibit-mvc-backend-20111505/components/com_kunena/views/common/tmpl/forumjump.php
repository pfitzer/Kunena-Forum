<?php
/**
 * @version $Id$
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2010 Kunena Team All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();
?>
<form id="jumpto" name="jumpto" method="post" target="_self" action="<?php echo KunenaRoute::_('index.php?option=com_kunena');?>">
	<span class="kright">
		<input type="hidden" name="func" value="showcat" />
		<?php echo $this->categorylist; ?>
		<input type="submit" name="Go" class="kbutton ks" value="<?php echo JText::_('COM_KUNENA_GO'); ?>" />
	</span>
</form>