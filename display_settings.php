<?php
if (!defined('ABSPATH')) {
  exit;
}

$dados_gerais = $bill->getBasicData();
$config_por_defeito = $bill->getDefaultConfig();
$stock_config = $woo_bill->getStockConfig();
$produto_config = $woo_bill->getProductConfig();
?>
<div class="wrap">
    <div class="tabs">
      <ul>
        <li class="<?php $bill->isActive('configuracoes'); ?>">
          <a href="admin.php?page=bill_settings">
            <?php echo __("Configurações", "bill-faturacao"); ?>
          </a>
        </li>
        <li class="<?php $bill->isActive('encomendas'); ?>">
          <a href="admin.php?page=bill_settings&tab=encomendas">
            <?php echo __("Encomendas", "bill-faturacao"); ?>
          </a>
        </li>
        <li class="<?php $bill->isActive('sync'); ?>">
          <a href="admin.php?page=bill_settings&tab=sync">
            <?php echo __("Sincronizar Artigos", "bill-faturacao"); ?>
          </a>
        </li>
        <li class="<?php $bill->isActive('sync-stock'); ?>">
          <a href="admin.php?page=bill_settings&tab=sync-stock">
            <?php echo __("Sincronizar Stock", "bill-faturacao"); ?>
          </a>
        </li>
        <li class="<?php $bill->isActive('documento'); ?> <?php $bill->isVisible('documento'); ?>">
          <a href="admin.php?page=bill_settings&tab=documento">
            <?php echo __("Ver Documento", "bill-faturacao"); ?>
          </a>
        </li>
        <li class="<?php $bill->isActive('synclog'); ?>">
          <a href="admin.php?page=bill_settings&tab=synclog">
            <?php echo __("Registos", "bill-faturacao"); ?>
          </a>
        </li>
      </ul>
    </div>
    <?php
    if ($bill->showConfiguracoes()) {
        include_once __DIR__ . '/templates/config.php';
    }
    if ($bill->showEncomendas()) {
        include_once __DIR__ . '/templates/encomendas.php';
    }

    if ($bill->showSyncPage()) {
        include_once __DIR__ . '/templates/syncprodutos.php';
    }

    if ($bill->showSyncStockPage()) {
        include_once __DIR__ . '/templates/syncstock.php';
    }

    if ($bill->showSyncLogPage()) {
      include_once __DIR__ . '/templates/synclog.php';
    }

    include_once __DIR__ . '/templates/documento.php';
    ?>
    <hr>
  </div>
  <?php $bill->printDebugFromMemory(); ?>