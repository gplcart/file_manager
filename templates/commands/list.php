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
    <form class="form-inline" method="post">
      <input type="hidden" name="token" value="<?php echo $_token; ?>">
      <div class="form-toolbar">
        <?php if (!empty($actions)) { ?>
        <div class="input-group">
          <span class="input-group-addon"><?php echo $this->text('With selected'); ?></span>
          <select class="form-control" name="command_id" data-ajax="false">
            <option value=""><?php echo $this->text('- do action -'); ?></option>
            <?php foreach ($actions as $command_id => $command) { ?>
            <option value="<?php echo $command_id; ?>"><?php echo $this->text($command['name']); ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group hidden-js">
          <button class="btn btn-default" name="process_selected" value="1"><?php echo $this->text('Go'); ?></button>
        </div>
        <?php } ?>
        <?php if (!empty($filters)) { ?>
        <div class="input-group filter hidden-no-js">
          <span class="input-group-addon"><?php echo $this->text('Show'); ?></span>
          <select class="form-control" name="filter_key">
            <option value=""><?php echo $this->text('Any'); ?></option>
            <?php foreach ($filters as $key => $filter) { ?>
            <option value="<?php echo $key; ?>"<?php echo isset($_query['filter_key']) && $_query['filter_key'] === $key ? ' selected' : ''; ?><?php echo isset($filter['input']) ? ' data-input="' . $filter["input"] . '"' : ''; ?>>
              <?php echo $this->text($filter['name']); ?>
            </option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group filter hidden-no-js">
          <input class="form-control" name="filter_value" value="<?php echo isset($_query['filter_value']) ? $this->e($_query['filter_value']) : ''; ?>">
        </div>
        <button class="btn btn-default filter hidden-no-js"><span title="<?php echo $this->text('Go'); ?>" class="fa fa-search"></span></button>
        <?php if (isset($_query['filter_key'])) { ?>
        <a href="<?php echo $this->url('', array('filter_key' => null)); ?>" class="btn btn-default hidden-no-js" title="<?php echo $this->text('Reset'); ?>"><span class="fa fa-refresh"></span></a>
        <?php } ?>
        <?php } ?>
        <?php if (!empty($pager)) { ?>
        <div class="form-group pull-right"><?php echo $pager; ?></div>
        <?php } ?>
      </div>
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
      <div class="content-wrapper">
        <?php if (empty($files)) { ?>
        <?php echo $this->text('No files to display'); ?>
        <?php } else { ?>
        <table class="table table-condensed">
          <thead>
            <tr class="active">
              <th><input type="checkbox" data-ajax="false" onchange="Gplcart.selectAll(this);"></th>
              <th>
                <?php echo $this->text('Name'); ?>
                <?php if (isset($sorters['name'])) { ?>
                <a href="<?php echo $this->url('', array('sort' => 'name', 'order' => 'desc') + $_query); ?>">
                  <i class="fa fa-sort-amount-desc"></i>
                </a>
                <a href="<?php echo $this->url('', array('sort' => 'name', 'order' => 'asc') + $_query); ?>">
                  <i class="fa fa-sort-amount-asc"></i>
                </a>
                <?php } ?>
              </th>
              <th>
                <?php echo $this->text('Type'); ?>
                <?php if (isset($sorters['type'])) { ?>
                <a href="<?php echo $this->url('', array('sort' => 'type', 'order' => 'desc') + $_query); ?>">
                  <i class="fa fa-sort-amount-desc"></i>
                </a>
                <a href="<?php echo $this->url('', array('sort' => 'type', 'order' => 'asc') + $_query); ?>">
                  <i class="fa fa-sort-amount-asc"></i>
                </a>
                <?php } ?>
              </th>
              <th>
                <?php echo $this->text('Modified'); ?>
                <?php if (isset($sorters['date'])) { ?>
                <a href="<?php echo $this->url('', array('sort' => 'date', 'order' => 'desc') + $_query); ?>">
                  <i class="fa fa-sort-amount-desc"></i>
                </a>
                <a href="<?php echo $this->url('', array('sort' => 'date', 'order' => 'asc') + $_query); ?>">
                  <i class="fa fa-sort-amount-asc"></i>
                </a>
                <?php } ?>
              </th>
              <th>
                <?php echo $this->text('Size'); ?>
                <?php if (isset($sorters['size'])) { ?>
                <a href="<?php echo $this->url('', array('sort' => 'size', 'order' => 'desc') + $_query); ?>">
                  <i class="fa fa-sort-amount-desc"></i>
                </a>
                <a href="<?php echo $this->url('', array('sort' => 'size', 'order' => 'asc') + $_query); ?>">
                  <i class="fa fa-sort-amount-asc"></i>
                </a>
                <?php } ?>
              </th>
              <th><?php echo $this->text('Permissions'); ?></th>
              <th><?php echo $this->text('Owner'); ?></th>
              <th><?php echo $this->text('Actions'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($files as $file) { ?>
            <tr data-path="<?php echo $this->e($file['path']); ?>">
              <td><input name="selected[]" type="checkbox" data-ajax="false" value="<?php echo $file['path']; ?>"<?php echo isset($selected[$file['path']]) ? ' checked' : ''; ?>></td>
              <td>
                <?php if ($this->access("module_file_manager_{$file['command']}")) { ?>
                <a href="<?php echo $this->url('', array('cmd' => $file['command'], 'path' => $file['path'])); ?>">
                  <?php echo $file['icon']; ?> <?php echo $this->e($file['info']->getBasename()); ?>
                </a>
                <?php } else { ?>
                <?php echo $file['icon']; ?> <?php echo $this->e($file['info']->getBasename()); ?>
                <?php } ?>
              </td>
              <td><?php echo $this->text('@type', array('@type' => $file['info']->getType())); ?></td>
              <td><?php echo $this->date($file['info']->getMTime()); ?></td>
              <td><?php echo $file['size']; ?></td>
              <td><?php echo $this->e($file['permissions']); ?></td>
              <td><?php echo $this->e($file['owner']); ?></td>
              <td>
                <?php if (!empty($file['commands'])) { ?>
                <div class="btn-group btn-group-xs">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu pull-right">
                    <li class="small">
                      <?php foreach ($file['commands'] as $id => $cmd) { ?>
                      <?php if ($this->access("module_file_manager_$id")) { ?>
                      <a href="<?php echo $this->url('', array('cmd' => $id, 'path' => $file['path'])); ?>">
                        <?php echo $this->text($cmd['name']); ?>
                      </a>
                      <?php } ?>
                      <?php } ?>
                    </li>
                  </ul>
                </div>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php } ?>
      </div>
    </form>
    <?php } ?>
  </div>
</div>