<?php
if (!defined('ABSPATH')) {
  exit;
}
?>
<div class="columns">
  <div class="column">
    <div class="box">
      <p class="subtitle"><strong><?php echo __("Registos", "bill-faturacao"); ?></strong></p>
      <?php if (isset($_GET['reset']) && $_GET['reset'] == 'log_database') {
        $woo_bill->deleteLogs();
      } ?>
      <div class="columns">
        <div class="column">
          <table class="table is-bordered is-fullwidth">
            <tr>
              <td><?php echo __("Data", "bill-faturacao"); ?></td>
              <td><?php echo __("Tipo", "bill-faturacao"); ?></td>
              <td><?php echo __("Resultado", "bill-faturacao"); ?></td>
            </tr>
            <?php
            foreach ($woo_bill->getLogs() as $log) {
              $log_data = json_decode($log->value);
              ?>
              <tr>
                <td><?php echo $log->date; ?></td>
                <td><?php echo $log->type; ?></td>
                <td style="word-break: break-all;">
                  <?php
                    if (strlen($log_data->success) > 0) {
                      echo '<span class="tag is-success">Success</span> ' . $log_data->success . '<br>';
                    } ?>
                  <?php
                    if (strlen($log_data->error) > 0) {
                      echo '<span class="tag is-danger">Error</span> ' . $log_data->error . '<br>';
                    } ?>
                </td>
              </tr>
            <?php } ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="column is-3">
    <div class="box">
      <p class="subtitle"><strong><?php echo __("Opções", "bill-faturacao"); ?></strong></p>
      <a href="admin.php?page=bill_settings&tab=synclog&reset=log_database" class="button is-fullwidth is-danger">Reset</a>
    </div>
  </div>
</div>