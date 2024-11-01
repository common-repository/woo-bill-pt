<?php
if (!defined('ABSPATH')) {
  exit;
}
?>
<div class="<?php $bill->isVisible('documento'); ?>">
  <div class="columns">
    <div class="column">
      <?php $bill->printDocument(); ?>
    </div>
  </div>
</div>