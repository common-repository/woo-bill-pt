<?php
if (!defined('ABSPATH')) {
  exit;
}
?>
<div class="columns">
  <div class="column">
    <div class="box">
      <p class="subtitle"><strong><?php echo __("Sincronizar Produtos", "bill-faturacao"); ?></strong></p>
      <div class="columns">
        <div class="column">
          <article class="message is-dark is-small">
            <div class="message-header">
              <p><?php echo __("Link Cronjob", "bill-faturacao"); ?></p>
            </div>
            <div class="message-body">
              <?php echo $woo_bill->getProductCronjobURL() ?>
            </div>
          </article>
          <p class="small"><?php echo __("Se desejar actualizar o stock de forma automática. Deverá configurar um cronjob no seu CPANEL.", "bill-faturacao"); ?></p>
          <hr>
          <table class="table is-bordered is-fullwidth">
            <tr>
              <td><?php echo __("Total em espera", "bill-faturacao"); ?></td>
              <td class="has-text-right">
                <?php
                  if (isset($_GET['reset']) 
                  && $_GET['reset'] == 'waiting_list') {
                    $woo_bill->deleteWaitingList();
                  }
                 echo $woo_bill->totalItemsWaiting();
             ?> Produtos</td>
              <td class="has-text-right">
              <a href="admin.php?page=bill_settings&tab=sync&reset=waiting_list" class="button is-small is-danger">Reset</a></td>
            </tr>
          </table>
          <table class="table is-bordered is-fullwidth">
            <tr>
              <td><?php echo __("Último Update", "bill-faturacao"); ?></td>
              <td class="has-text-right"><?php
                  if (isset($_GET['reset']) 
                  && $_GET['reset'] == 'last_product_sync') {
                    $woo_bill->deleteConfigValue('last_product_sync');
                  }
                  $start = $woo_bill->getLastSyncTime('last_product_sync');
                  if ($start > 0) {
                    $time = date('Y-m-d H:i:s', $start);
                    echo $time;
                  }

                  ?> <a href="admin.php?page=bill_settings&tab=sync&reset=last_product_sync" class="button is-small is-danger">Reset</a></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="box">
      <p class="subtitle"><strong><?php echo __("Modo de Importação", "bill-faturacao"); ?></strong></p>
      <strong><?php echo __("Perceba os modos de importação", "bill-faturacao"); ?></strong>
      <br>
      <small><strong><?php echo __("Avançada", "bill-faturacao"); ?></strong> <?php echo __("- Para quem já tem campos extra criados no Bill e pretende connectar esses campos e informação ao Woocommerce.", "bill-faturacao"); ?></small>
      <br>
      <small><strong><?php echo __("Simples", "bill-faturacao"); ?></strong> <?php echo __("- Irá criar os campos equivalentes ao Woocommerce no Bill. Terá de preencher depois esses campos e automaticamente quando importado irá actualizar a sua loja.", "bill-faturacao"); ?></small>
      <br>
      <br>
      <?php if (
        isset($produto_config->modo_de_importacao)
        && $produto_config->modo_de_importacao == "2"
      ) { ?>
        <table class="table is-fullwidth">
          <thead>
            <tr>
              <th><a href="admin.php?page=bill_settings&tab=sync&create_extra_fields=basico" class="button is-info"><?php echo __("Criar Campos Básicos", "bill-faturacao"); ?></a></th>
              <th><a href="admin.php?page=bill_settings&tab=sync&create_extra_fields=all" class="button is-success"><?php echo __("Criar Todos os Campos", "bill-faturacao"); ?></a></th>
            </tr>
          </thead>
          <tbody>
            <?php
              $simple = array_flip($woo_bill->getSimpleMap());
              foreach ($woo_bill->getMap() as $key => $value) { ?>
              <tr>
                <td><?php
                    if (!empty($simple)) {
                      echo array_shift($simple);
                    } ?></td>
                <td><?php
                    echo $key;
                    ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } ?>
      <?php if (
        isset($produto_config->modo_de_importacao)
        && $produto_config->modo_de_importacao == "1"
      ) { ?>
        <p class="subtitle"><strong><?php echo __("Importação Avançada - Mapear informação", "bill-faturacao"); ?></strong></p>
        <p style="margin-bottom:5px"><?php 
        echo __("Faça a ligação entre os dados do Woocommerce e os dados do Bill.", "bill-faturacao"); ?> <strong><?php echo __("Não se esqueça de gravar no fim da página.", "bill-faturacao"); ?></strong></p>
        <p style="margin-bottom:5px"><?php 
        echo ' <span class="tag is-info">SKU</span>';
        echo ' ',  __("Não atribuir uma coluna, a menos que saiba o que está a fazer! Por defeito será usado o código do bill.", "bill-faturacao"); ?></p>
        <p style="margin-bottom:5px"><?php 
        echo ' <span class="tag is-info">Name</span>';
        echo ' ',  __("Por defeito será usado o campo Descrição mas poderá atribuir outro.", "bill-faturacao"); ?></p>
        <p style="margin-bottom:5px"><?php 
        echo ' <span class="tag is-info">Images</span>';
        echo ' ',  __("Este campo será automaticamente preenchido com os valores de campos que tenham a palavra Image ou image ex: image_1 ou Imagem 1", "bill-faturacao"); ?></p>
         <p style="margin-bottom:5px"><?php 
        echo ' <span class="tag is-info">Attributes</span>';
        echo ' ',  __("Os campos extra deverão ser definidos no bill começando com o nome Attribute seguido do caracter espaço e nome do campo extra", "bill-faturacao"); ?></p>
        <hr>
        <?php
          $structure = $woo_bill->getItemMetaStructurePlucked();
          ?>
        <table class="table is-fullwidth is-bordered">
          <thead>
            <tr>
              <th>Woocommerce</th>
              <th>Bill</th>
            </tr>
          </thead>
          <tbody>
            <form method="POST">
              <input type="hidden" name="update_custom_map" value="1">
              <?php
                $map = $woo_bill->getMap();
                $custom_map = $woo_bill->getCustomMap();
                foreach ($map as $key => $value) {
                  if( strpos($key, 'Attribute ') !== false
                  ||  strpos($key, 'Download ') !== false ){
                    continue;
                  }
                ?>
                <tr>
                  <td><?php
                          echo $key;
                          if ($key == 'Name') {
                            echo ' <span class="tag is-info">',  __("*", "bill-faturacao"), '</span>';
                          }
                          if ($key == 'SKU') {
                            echo ' <span class="tag is-info">',  __("*", "bill-faturacao"), '</span>';
                          }
                          ?></td>
                  <td>
                    <select name="custom_map[<?php echo $key ?>]" class="select is-fullwidth">
                      <option></option>
                      <?php
                        foreach ($structure as $value) {
                          if( $value == "order"){
                            continue;
                          }
                      ?>
                        <option value="<?php echo $value ?>" <?php selected(isset($custom_map->{$key}) ? $custom_map->{$key} : 0, $value, true); ?>><?php echo $value ?></option>
                      <?php }
                          ?>
                    </select></td>
                </tr>
              <?php } ?>
              <tr>
                <td></td>
                <td>
                  <input type="submit" value="<?php echo __("Update ", "bill-faturacao "); ?>" class="button is-fullwidth is-success" />
                </td>
              </tr>
            </form>
          </tbody>
        </table>
      <?php } ?>
    </div>
  </div>
  <div class="column is-3">
    <div class="box">
      <p class="subtitle"><strong><?php echo __("Opções de Sincronismo", "bill-faturacao"); ?></strong></p>
      <form method="POST">
        <input type="hidden" name="update_sincronismo_produto" value="1">
        <div class="field">
          <div class="control">
            <label class="label">
              <?php echo __("Loja", "bill-faturacao"); ?>
            </label>
            <div class="select is-fullwidth">
              <select name="loja_produtos" id="loja_produtos">
                <?php $bill->populateSelectGeneral($dados_gerais['loja'], $produto_config->loja_produtos, 0, true); ?>
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <label class="label">
              <?php echo __("Sincronizar Produtos", "bill-faturacao"); ?>
            </label>
            <div class="select is-fullwidth">
              <select name="sincronizar_produto" id="">
                <?php $bill->populateSelectGeneral(['0' => 'Não', '1' => 'Sim'], $produto_config->sincronizar_produto, 0); ?>
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
                <?php $bill->populateSelectGeneral(['5' => 'Cada 5 minutos', '30' => 'Cada 30 minutos', '60' => 'Cada 60 Minutos'], $produto_config->frequencia, 5); ?>
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <label class="label">
              <?php echo __("Modo de Importação", "bill-faturacao"); ?>
            </label>
            <div class="select is-fullwidth">
              <select name="modo_de_importacao" id="modo_de_importacao">
                <?php $bill->populateSelectGeneral(['1' => 'Importação Avançada', '2' => 'Importação Simples'], $produto_config->modo_de_importacao, 5); ?>
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <div class="control">
            <input type="submit" value="<?php echo __("Update ", "bill-faturacao "); ?>" class="button is-fullwidth is-success" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>