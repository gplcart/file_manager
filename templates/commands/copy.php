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
    <form method="post">
      <input type="hidden" name="token" value="<?php echo $_token; ?>">
      <?php if ($process_selected && !empty($selected)) { ?>
      <?php echo $this->text('Copy selected files:'); ?>
      <?php $selected_files = array(); ?>
      <?php foreach ($selected as $selected_path => $selected_file) { ?>
      <?php $selected_files[] = $this->e($selected_file->getBasename()); ?>
      <?php } ?>
      <div class="selected-files"><?php echo implode(', ', $selected_files); ?></div>
      <?php } ?>
      <div class="form-group required<?php echo $this->error('destination', ' has-error'); ?>">
        <label><?php echo $this->text('Destination'); ?></label>
        <div class="input-group">
          <span class="input-group-addon"><?php echo $this->e($path); ?>/</span>
          <input name="filemanager[destination]" class="form-control" value="<?php echo isset($filemanager['destination']) ? $this->e($filemanager['destination']) : ''; ?>">
        </div>
        <div class="help-block">
          <?php echo $this->error('destination'); ?>
          <div class="text-muted"><?php echo $this->text('Path to an existing directory. Trailing slashes are not allowed'); ?></div>
        </div>
      </div>
      <button class="btn btn-default" name="submit" value="1"><?php echo $this->text('Copy'); ?></button>
    </form>
    <?php } ?>
  </div>
</div>
