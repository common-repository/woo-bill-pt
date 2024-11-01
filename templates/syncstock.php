<?php
if (!defined('ABSPATH')) {
  exit;
}
?>
<div class="columns">
  <div class="column">
    <div class="box">
      <p class="subtitle"><strong><?php echo __("Sincronizar Stock", "bill-faturacao"); ?></strong></p>
      <div class="columns">
        <div class="column">
          <article class="message is-dark is-small">
            <div class="message-header">
              <p><?php echo __("Link Cronjob", "bill-faturacao"); ?></p>
            </div>
            <div class="message-body">
              <?php echo $woo_bill->getStockCronjobURL() ?>
            </div>
          </article>
          <p class="small"><?php echo __("Se desejar actualizar o stock de forma automática. Deverá configurar um cronjob no seu CPANEL.", "bill-faturacao"); ?></p>
          <hr>
          <table class="table is-bordered is-fullwidth">
            <tr>
              <td><?php echo __("Último Update", "bill-faturacao"); ?></td>
              <td><?php
                  if(isset($_GET['reset']) && $_GET['reset'] == 'last_stock_sync'){
                      $woo_bill->deleteConfigValue('last_stock_sync');
                  }
                  $start = $woo_bill->getLastSyncTime('last_stock_sync');
                  if ($start > 0) {
                    $time = date('Y-m-d H:i:s', $start);
                    echo $time;
                  }
                  
                  ?> <a href="admin.php?page=bill_settings&tab=sync-stock&reset=last_stock_sync" class="button is-small is-danger">Reset</a></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="column is-3">
    <div class="box">
      <p class="subtitle"><strong><?php echo __("Opções de Sincronismo", "bill-faturacao"); ?></strong></p>
      <form method="POST">
        <input type="hidden" name="update_sincronismo_stock" value="1">
        <div class="field">
          <div class="control">
            <label class="label">
              <?php echo __("Loja", "bill-faturacao"); ?>
            </label>
            <div class="select is-fullwidth">
              <select name="loja_stock" id="loja_stock">
                <?php $bill->populateSelectGeneral($dados_gerais['loja'], $stock_config->loja_stock, 0, true); ?>
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <label class="label">
              <?php echo __("Sincronizar Stock", "bill-faturacao"); ?>
            </label>
            <div class="select is-fullwidth">
              <select name="sincronizar_stock" id="">
                <?php $bill->populateSelectGeneral(['0' => 'Não', '1' => 'Sim'], $stock_config->sincronizar_stock, 0); ?>
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <label class="label">
              <?php echo __("Frequencia Sincronismo", "bill-faturacao"); ?>
            </label>
            <div class="select is-fullwidth">
              <select name="frequencia" id="frequencia">
                <?php $bill->populateSelectGeneral(['1' => 'Cada 1 minuto', '5' => 'Cada 5 minutos', '15' => 'Cada 15 Minutos', '30' => 'Cada 30 Minutos'], $stock_config->frequencia, 5); ?>
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="submit" value="<?php echo __(" Update ", "bill-faturacao "); ?>" class="button is-fullwidth is-success" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>