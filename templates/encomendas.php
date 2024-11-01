<?php
if (!defined('ABSPATH')) {
  exit;
}
?>
<div class="<?php $bill->isVisible('encomendas'); ?>">
  <div class="box">
    <table class="widefat">
      <thead>
        <tr>
          <th>
            <?php echo __("Order", "bill-faturacao"); ?>
          </th>
          <th>
            <?php echo __("Nome", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Total", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Orçamento", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Encomenda", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Guia Transporte & Fatura", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Fatura", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Fatura Recibo", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Fatura Simplificada", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Recibo", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Date", "bill-faturacao") ?>
          </th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>
            <?php echo __("Order", "bill-faturacao"); ?>
          </th>
          <th>
            <?php echo __("Nome", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Total", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Orçamento", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Encomenda", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Guia Transporte & Fatura", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Fatura", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Fatura Recibo", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Fatura Simplificada", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Recibo", "bill-faturacao") ?>
          </th>
          <th>
            <?php echo __("Date", "bill-faturacao") ?>
          </th>
        </tr>
      </tfoot>
      <tbody>
        <?php $max_page = $bill->getAllOrders(); ?>
      </tbody>
    </table>
    <?php
if ($max_page > 0) {
    bill_pt_pagination($max_page);
} ?>
  </div>
</div>