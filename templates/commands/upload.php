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
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
      <input type="hidden" name="token" value="<?php echo $_token; ?>">
      <div class="form-group<?php echo $this->error('files', ' has-error'); ?>">
        <div class="col-md-12">
          <input id="filemanager-upload" type="file" name="files[]" multiple>
          <div class="help-block">
            <?php echo $this->error('files'); ?>
          </div>
        </div>
      </div>
      <button class="btn btn-default" name="submit" value="1"><?php echo $this->text('Upload'); ?></button>
    </form>
    <?php } ?>
  </div>
</div>
