<div class="filemanager">
  <?php if (!empty($messages)) { ?>
  <div class="row">
    <div class="col-md-12">
      <?php foreach ($messages as $type => $strings) { ?>
      <div class="alert alert-<?php echo $this->e($type); ?> alert-dismissible fade in">
        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
        <?php foreach ($strings as $string) { ?>
        <?php echo $this->filter($string); ?><br>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>
  <?php if (empty($access)) { ?>
  <?php echo $this->text('No access'); ?>
  <?php } else { ?>
  <?php if (!empty($tabs)) { ?>
  <ul class="nav nav-tabs">
    <?php foreach ($tabs as $name => $tab) { ?>
    <?php if (isset($command['tab']) && $command['tab'] === $name) { ?>
    <li class="active"><a class="disabled"><?php echo $tab['text']; ?></a></li>
    <?php } else { ?>
    <li><a href="<?php echo $tab['url']; ?>"><?php echo $tab['text']; ?></a></li>
    <?php } ?>
    <?php } ?>
  </ul>
  <?php } ?>
  <div class="row middle">
    <div class="region-content col-md-12">
      <?php if (!empty($content)) { ?>
      <?php echo $content; ?>
      <?php } ?>
    </div>
  </div>
  <?php } ?>
</div>