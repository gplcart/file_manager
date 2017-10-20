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
    <?php if (!empty($breadcrumbs)) { ?>
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
    <?php } ?>
    <form method="post">
      <input type="hidden" name="token" value="<?php echo $_token; ?>">
      <div class="form-group required<?php echo $this->error('name', ' has-error'); ?>">
        <label><?php echo $this->text('Name'); ?></label>
        <div class="input-group">
          <span class="input-group-addon"><?php echo $this->e($path); ?>/</span>
          <input name="filemanager[name]" class="form-control" value="<?php echo isset($filemanager['name']) ? $this->e($filemanager['name']) : $this->e($name) ?>">
        </div>
        <div class="help-block">
          <?php echo $this->error('name'); ?>
          <div class="text-muted"><?php echo $this->text('File name must contain only alphanumeric characters, underscores and dashes'); ?></div>
        </div>
      </div>
      <button class="btn btn-default" name="submit" value="1"><?php echo $this->text('Save'); ?></button>
    </form>
    <?php } ?>
  </div>
</div>
