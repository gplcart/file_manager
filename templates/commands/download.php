<?php
/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
?>
<div class="tab-content">
  <div class="col-md-12">
    <?php if(empty($access)) { ?>
    <?php echo $this->text('No access'); ?>
    <?php } else { ?>
    <ol class="breadcrumb">
      <?php foreach ($breadcrumbs as $index => $breadcrumb) { ?>
      <li>
        <?php if(isset($breadcrumb['path'])) { ?>
        <a href="<?php echo $this->url('', array('path' => $breadcrumb['path'])); ?>">
          <?php echo $this->e($breadcrumb['text']); ?>
        </a>
        <?php } else { ?>
        <?php echo $this->e($breadcrumb['text']); ?>
        <?php } ?>
      </li>
      <?php } ?>
    </ol>
    <form class="form-horizontal" method="post">
      <input type="hidden" name="token" value="<?php echo $_token; ?>">
      <?php if ($process_selected && !empty($selected)) { ?>
      <?php echo $this->text('Zip and download the following files:'); ?>
      <?php $selected_files = array(); ?>
      <?php foreach ($selected as $selected_path => $selected_file) { ?>
      <?php /* @var $selected_file \SplFileInfo */ $selected_files[] = $this->e($selected_file->getBasename()); ?>
      <?php } ?>
      <div class="selected-files"><?php echo implode(', ', $selected_files); ?></div>
      <?php } ?>
      <button class="btn btn-default" name="submit" value="1"><?php echo $this->text('Download'); ?></button>
    </form>
    <?php } ?>
  </div>
</div>