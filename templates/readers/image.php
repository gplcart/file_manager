<?php
/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
?>
<?php if (!empty($exif)) { ?>
<ul class="list-unstyled">
  <?php if (isset($exif['COMPUTED']['Height'])) { ?>
  <li><?php echo $this->text('Height'); ?>: <?php echo $this->e($exif['COMPUTED']['Height']); ?>px</li>
  <?php } ?>
  <?php if (isset($exif['COMPUTED']['Width'])) { ?>
  <li><?php echo $this->text('Width'); ?>: <?php echo $this->e($exif['COMPUTED']['Width']); ?>px</li>
  <?php } ?>
  <?php if (!empty($exif['COMMENT'])) { ?>
  <?php foreach ($exif['COMMENT'] as $comment) { ?>
  <li><?php echo $this->e($comment); ?></li>
  <?php } ?>
  <?php } ?>
</ul>
<?php } ?>
<img class="img-responsive" src="<?php echo $this->e($src); ?>">
