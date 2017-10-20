<?php
/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
?>
<ul class="list-unstyled">
  <?php /* @var $file \SplFileInfo */ ?>
  <li><?php echo $this->text('Modified: @date', array('@date' => $this->date($file->getMTime()))); ?></li>
  <li><?php echo $this->text('Size: @size', array('@size' => $filesize)); ?></li>
  <li><?php echo $this->text('Permissions: @permissions', array('@permissions' => $perms)); ?></li>
  <li><?php echo $this->text('Owner: @owner', array('@owner' => $file->getOwner())); ?></li>
</ul>
