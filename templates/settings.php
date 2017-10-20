<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
?>
<form method="post" class="form-horizontal">
  <input type="hidden" name="token" value="<?php echo $_token; ?>">
  <div class="form-group required<?php echo $this->error('initial_path', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('Initial path'); ?></label>
    <div class="col-md-6">
      <div class="input-group">
        <span class="input-group-addon"><?php echo $this->e($file_dir); ?>/</span>
        <input name="settings[initial_path]" class="form-control" value="<?php echo isset($settings['initial_path']) ? $this->e($settings['initial_path']) : ''; ?>">
      </div>
      <div class="help-block">
        <?php echo $this->error('initial_path'); ?>
        <div class="text-muted"><?php echo $this->text('A path relative to the system file directory. No trailing slashes'); ?></div>
      </div>
    </div>
  </div>
  <div class="form-group required<?php echo $this->error('limit', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('Limit'); ?></label>
    <div class="col-md-6">
      <input name="settings[limit]" class="form-control" value="<?php echo isset($settings['limit']) ? $this->e($settings['limit']) : ''; ?>">
      <div class="help-block">
        <?php echo $this->error('limit'); ?>
        <div class="text-muted"><?php echo $this->text('How many files to show per page'); ?></div>
      </div>
    </div>
  </div>
  <div class="form-group <?php echo $this->error('access', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('Path access'); ?></label>
    <div class="col-md-6">
      <textarea rows="5" name="settings[access]" class="form-control"><?php echo isset($settings['access']) ? $this->e($settings['access']) : ''; ?></textarea>
      <div class="help-block">
        <?php echo $this->error('access'); ?>
        <div class="text-muted"><?php echo $this->text('List of rules to access file paths. One rule per line in format <a href="@url">[numeric role ID]</a>[whitespace][regexp pattern]. All paths should be relative to the system file directory %dir and without trailing slashes. If no rules defined for a role then access to all paths will be controlled by %perm permission set for the role', array('@url' => $this->url('admin/user/role'), '%dir' => $file_dir, '%perm' => $this->text('File manager: access'))); ?></div>
      </div>
    </div>
  </div>
  <div class="form-group <?php echo $this->error('extension_limit', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('Allowed extensions'); ?></label>
    <div class="col-md-6">
      <textarea rows="5" name="settings[extension_limit]" class="form-control"><?php echo isset($settings['extension_limit']) ? $this->e($settings['extension_limit']) : ''; ?></textarea>
      <div class="help-block">
        <?php echo $this->error('extension_limit'); ?>
        <div class="text-muted"><?php echo $this->text('List of rules to control allowed extensions of uploaded files. One rule per line in format <a href="@url">[numeric role ID]</a>[whitespace][file extensions]. Separate multiple extensions with comma. If no rules defined for a role then the user can upload any file', array('@url' => $this->url('admin/user/role'))); ?></div>
      </div>
    </div>
  </div>
  <div class="form-group <?php echo $this->error('filesize_limit', ' has-error'); ?>">
    <label class="col-md-2 control-label"><?php echo $this->text('File size limits'); ?></label>
    <div class="col-md-6">
      <textarea rows="5" name="settings[filesize_limit]" class="form-control"><?php echo isset($settings['filesize_limit']) ? $this->e($settings['filesize_limit']) : ''; ?></textarea>
      <div class="help-block">
        <?php echo $this->error('filesize_limit'); ?>
        <div class="text-muted"><?php echo $this->text('List of rules to control max size of uploaded files. One rule per line in format <a href="@url">[numeric role ID]</a>[whitespace][size in bytes]. If no rules defined then file size will be controlled by PHP', array('@url' => $this->url('admin/user/role'))); ?></div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-4 col-md-offset-2">
      <div class="btn-toolbar">
        <a href="<?php echo $this->url('admin/module/list'); ?>" class="btn btn-default"><?php echo $this->text("Cancel"); ?></a>
        <button class="btn btn-default save" name="save" value="1"><?php echo $this->text("Save"); ?></button>
      </div>
    </div>
  </div>
</form>