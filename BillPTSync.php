<?php
if (!defined('ABSPATH')) {
    exit;
}
class BillPTSync
{
	protected $api, $stock_config, $categories;

	protected $simple_map = array(
		'SKU'                     => 'sku',
		'Name'                    => 'name',
		'Weight (kg)'             => 'weight',
		'Length (cm)'             => 'length',
		'Width (cm)'              => 'width',
		'Height (cm)'             => 'height',
		'Sale price'              => 'sale_price',
		'Regular price'           => 'regular_price',
		'Images'                  => 'images'
	);

	protected $simple_format = array(
		'SKU'                     => 'text',
		'Name'                    => 'text',
		'Weight (kg)'             => 'text',
		'Length (cm)'             => 'text',
		'Width (cm)'              => 'text',
		'Height (cm)'             => 'text',
		'Sale price'              => 'text',
		'Regular price'           => 'text',
		'Image 1' => 'image',
		'Image 2' => 'image',
		'Image 3' => 'image',
		'Image 4' => 'image',
	);

	protected $map = array(
		'Type'                    => 'type',
		'SKU'                     => 'sku',
		'Name'                    => 'name',
		'Published'               => 'published',
		'Is featured?'            => 'featured',
		'Visibility in catalog'   => 'catalog_visibility',
		'Short description'       => 'short_description',
		'Description'             => 'description',
		'Date sale price starts'  => 'date_on_sale_from',
		'Date sale price ends'    => 'date_on_sale_to',
		'Tax status'              => 'tax_status',
		'Tax class'               => 'tax_class',
		'In stock?'               => 'stock_status',
		'Backorders allowed?'     => 'backorders',
		'Sold individually?'      => 'sold_individually',
		'Weight (kg)'             => 'weight',
		'Length (cm)'             => 'length',
		'Width (cm)'              => 'width',
		'Height (cm)'             => 'height',
		'Allow customer reviews?' => 'reviews_allowed',
		'Purchase note'           => 'purchase_note',
		'Sale price'              => 'sale_price',
		'Regular price'           => 'regular_price',
		'Categories'              => 'category_ids',
		'Tags'                    => 'tag_ids',
		'Shipping class'          => 'shipping_class_id',
		'Images'                  => 'images',
		'Download limit'          => 'download_limit',
		'Download expiry days'    => 'download_expiry',
		'Parent'                  => 'parent_id',
		'Upsells'                 => 'upsell_ids',
		'Cross-sells'             => 'cross_sell_ids',
		'Grouped products'        => 'grouped_products',
		'External URL'            => 'product_url',
		'BUTTON TEXT'             => 'button_text',
		'Position'                => 'menu_order',
		'Attribute 1 Name'        => 'attributes:name1',
		'Attribute 1 Value(s)'    => 'attributes:value1',
		'Attribute 1 visible'    =>  'attributes:visible1',
		'Attribute 1 global'   => 'attributes:taxonomy1',
		'Attribute 2 Name'        => 'attributes:name2',
		'Attribute 2 Value(s)'    => 'attributes:value2',
		'Attribute 2 visible'    =>  'attributes:visible2',
		'Attribute 2 global'   		=> 'attributes:taxonomy2',
		'Download 1 name'         => 'downloads:name1',
		'Download 1 URL'          => 'downloads:url1',
		'Download 2 name'         => 'downloads:name2',
		'Download 2 URL'          => 'downloads:url2'
	);

	protected $woo_format = array(
		'Type' => 'select|simple|downloadable|virtual|variation|grouped|external',
		'SKU'                     => 'text',
		'Name'                    => 'text',
		'Published'               => 'checkbox',
		'Is featured?'            => 'checkbox',
		'Visibility in catalog'   => 'select|visible|catalog|search|hidden',
		'Short description'       => 'text',
		'Description'             => 'textarea',
		'Date sale price starts'  => 'text',
		'Date sale price ends'    => 'text',
		'Tax status'              => 'select|taxable|no',
		'Tax class'               => 'text',
		'In stock?'               => 'checkbox',
		'Backorders allowed?'     => 'checkbox',
		'Sold individually?'      => 'checkbox',
		'Weight (kg)'             => 'text',
		'Length (cm)'             => 'text',
		'Width (cm)'              => 'text',
		'Height (cm)'             => 'text',
		'Allow customer reviews?' => 'checkbox',
		'Purchase note'           => 'text',
		'Sale price'              => 'text',
		'Regular price'           => 'text',
		'Categories'              => 'text',
		'Tags'                    => 'text',
		'Shipping class'          => 'text',
		'Image 1' => 'image',
		'Image 2' => 'image',
		'Image 3' => 'image',
		'Image 4' => 'image',
		'Download limit'          => 'text',
		'Download expiry days'    => 'text',
		'Parent'                  => 'text',
		'Upsells'                 => 'text',
		'Cross-sells'             => 'text',
		'Grouped products'        => 'text',
		'External URL'            => 'text',
		'BUTTON TEXT'             => 'text',
		'Position'                => 'text',
		'Attribute 1 Name'        => 'text',
		'Attribute 1 Value(s)'    => 'text',
		'Attribute 1 visible'    =>  'text',
		'Attribute 1 global'   => 'text',
		'Attribute 2 Name'        => 'text',
		'Attribute 2 Value(s)'    => 'text',
		'Attribute 2 visible'    =>  'text',
		'Attribute 2 global'   		=> 'text',
		'Download 1 name'         => 'text',
		'Download 1 URL'          => 'text1',
		'Download 2 name'         => 'text',
		'Download 2 URL'          => 'text'
	);

	protected $custom_map = [];

	protected $exclude_fields = [
		'Image 1', 'Image 2', 'Image 3', 'Image 4',
		'Attribute 1 Name', 'Attribute 1 Value(s)',
		'Attribute 2 Name', 'Attribute 2 Value(s)'
	];

	protected $cast_integer = [
		'Published'
	];

	protected $default_values = [
		'Type' => 'simple',
		'Visibility in catalog' => 'visible',
		'Tax status' => 'taxable',
		'Published' => "1"
	];

	protected $mandatory = ['Name'];

	public function __construct($api)
	{
		$this->api = $api;
	}

	public function checkToken($token_name)
	{
		$token = $this->getConfigValue($token_name);
		if (!isset($_GET[$token_name]) || $token == "" || $token != $_GET[$token_name]) {
			return false;
		}
		return true;
	}

	public function generateRandomToken()
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 64; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public function getPortugalDateTime($timestamp)
	{
		$reset = date_default_timezone_get();
		date_default_timezone_set('Europe/London');
		$date = date('Y-m-d H:i:s', $timestamp);
		date_default_timezone_set($reset);
		return $date;
	}

	public function updateSkuToID()
	{
		$products = wc_get_products(['limit' => -1]);
		foreach ($products as $product) {
			$json[$product->get_sku()] = $product->get_id();
		}

		if (!isset($json)) {
			$json = [];
		}

		global $wpdb;
		$wpdb->delete(
			'bill_config',
			array('config' => 'sku_to_id')
		);

		$wpdb->insert('bill_config', [
			'config' => 'sku_to_id', 'value' => json_encode($json)
		], ['%s', '%s']);
	}

	public function insertLog($type, $value)
	{
		global $wpdb;
		$wpdb->insert('bill_sync_log', [
			'date' => $this->getPortugalDateTime(time()), 'type' => $type, 'value' => json_encode($value)
		], ['%s', '%s']);
	}

	public function getLogs()
	{
		global $wpdb;
		$logs = $wpdb->get_results('SELECT * FROM bill_sync_log ORDER BY id DESC');
		
		if( $wpdb->num_rows > 500 ){
			$wpdb->query('DELETE FROM bill_sync_log ORDER BY id ASC LIMIT 50');
		}
		
		return $logs;
	}

	public function deleteLogs()
	{
		global $wpdb;
		$wpdb->query('DELETE FROM bill_sync_log');
	}

	public function deleteWaitingList()
	{
		global $wpdb;
		$wpdb->query('DELETE FROM bill_produtos_sync');
	}

	public function getSkuToID()
	{
		global $wpdb;
		$sku_to_id = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "sku_to_id"');

		if (isset($sku_to_id->value) 
		&& strlen($sku_to_id->value) > 5) {
			$sku_to_id = json_decode($sku_to_id->value);
		} else {
			$this->updateSkuToID();
			$sku_to_id = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "sku_to_id"');
			$sku_to_id = json_decode($sku_to_id->value);
		}

		$this->sku_to_id = $sku_to_id;
		return $sku_to_id;
	}

	public function syncProductList()
	{
		if (!$this->checkToken('key_products_cron')) {
			return false;
		}
		
		$data = date('Y-m-d H:i:s', 1);
		$last_update = $this->getLastSyncTime('last_product_sync');
		$time = $this->api->getServerTime();
		$server_time = $time->time;
		$server_time = $server_time - 3600;
		$this->next_page = 1;

		if ($last_update > 0 && !isset($_GET['update_all'])) {
			$data = $this->getPortugalDateTime($last_update);
		}

		$this->updateProductConfig();
		$product_config = $this->getProductConfig();
		if ($product_config->sincronizar_produto != "1") {
			return false;
		}

		$pesquisa = ['last_update' => $data, 'limite' => 200, 'embed_meta' => true, 'embed_categorias' => true];

		if (isset($product_config->loja_produtos) 
		&& is_numeric($product_config->loja_produtos) 
		&& $product_config->loja_produtos > 0) {
			$pesquisa['pesquisa']['loja'][] = $product_config->loja_produtos;
		}

		do {
			$pesquisa['page'] = $this->next_page;
			$items = $this->api->getItems($pesquisa);
			$this->saveItemsInDatabase($items);
			$this->next_page = $this->getNextPage($items);
		} while ($this->next_page != null);
		$this->updateLastSyncTime('last_product_sync', $server_time);
		$this->generateCSVFilesFromDatabase();
	}

	public function getStockCronjobURL()
	{
		$stock_config = $this->getStockConfig();
		if (!isset($stock_config->sincronizar_stock) 
		|| $stock_config->sincronizar_stock != "1") {
			return "";
		}

		$url = get_bloginfo('url');
		$cronjob = $this->getConfigValue('key_stock_cron');
		if ($cronjob == "") {
			$cronjob = $this->generateRandomToken();
			$this->updateConfigValue('key_stock_cron', $cronjob);
		}

		$frequencia = '*';

		if (isset($stock_config->frequencia) 
		&& $stock_config->frequencia != "1") {
			$frequencia = '*/' .  $stock_config->frequencia;
		}

		return  $frequencia . ' * * * * wget -O- ' . $url . '?key_stock_cron=' . $cronjob . ' >> /dev/null';
	}

	public function getProductCronjobURL()
	{
		$this->updateProductConfig();
		$product_config = $this->getProductConfig();
		if (!isset($product_config->sincronizar_produto) 
		|| $product_config->sincronizar_produto != "1") {
			return false;
		}

		$url = get_bloginfo('url');
		$cronjob = $this->getConfigValue('key_products_cron');
		if ($cronjob == "") {
			$cronjob = $this->generateRandomToken();
			$this->updateConfigValue('key_products_cron', $cronjob);
		}

		$frequencia = '*';

		if (isset($product_config->frequencia) 
		&& $product_config->frequencia != "1") {
			$frequencia = '*/' .  $product_config->frequencia;
		}

		return $frequencia . ' * * * * wget -O- ' . $url . '?key_products_cron=' . $cronjob . ' >> /dev/null';
	}

	public function getStockConfig()
	{
		if (!is_null($this->stock_config)) {
			return $this->stock_config;
		}
		global $wpdb;

		$stock_config = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "stock_config"');

		if (isset($stock_config->value) 
		&& strlen($stock_config->value) > 3) {
			$stock_config = json_decode($stock_config->value);
		}

		$this->stock_config = $stock_config;
		return $stock_config;
	}

	public function updateStockConfig()
	{
		global $wpdb;
		if (isset($_POST['update_sincronismo_stock'])) {
			$wpdb->delete('bill_config', array('config' => 'stock_config'));

			if (isset($_POST['loja_stock'])) {
				$config['loja_stock'] = (int) $_POST['loja_stock'];
			}

			if (isset($_POST['sincronizar_stock'])) {
				$config['sincronizar_stock'] = (int) $_POST['sincronizar_stock'];
			}

			if (isset($_POST['frequencia'])) {
				$config['frequencia'] = (int) $_POST['frequencia'];
			}

			$wpdb->insert('bill_config', [
				'config' => 'stock_config', 'value' => json_encode($config)
			], ['%s', '%s']);
		}

		$stock_config = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "stock_config"');

		if (isset($stock_config->value) 
		&& strlen($stock_config->value) > 3) {
			$stock_config = json_decode($stock_config->value);
		}

		$this->stock_config = $stock_config;
	}

	public function getProductConfig()
	{
		if (!is_null($this->product_config)) {
			return $this->product_config;
		}
		global $wpdb;

		$product_config = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "product_config"');

		if (isset($product_config->value) 
		&& strlen($product_config->value) > 3) {
			$product_config = json_decode($product_config->value);
		}

		$this->product_config = $product_config;
		return $product_config;
	}

	public function updateProductConfig()
	{
		global $wpdb;
		if (isset($_POST['update_sincronismo_produto'])) {
			$wpdb->delete('bill_config', array('config' => 'product_config'));

			if (isset($_POST['loja_produtos'])) {
				$config['loja_produtos'] = (int) $_POST['loja_produtos'];
			}

			if (isset($_POST['sincronizar_produto'])) {
				$config['sincronizar_produto'] = (int) $_POST['sincronizar_produto'];
			}

			if (isset($_POST['frequencia'])) {
				$config['frequencia'] = (int) $_POST['frequencia'];
			}

			if (isset($_POST['modo_de_importacao'])) {
				$_POST['modo_de_importacao'] = ($_POST['modo_de_importacao'] == "") ? 2 : $_POST['modo_de_importacao'];
				$config['modo_de_importacao'] = (int) $_POST['modo_de_importacao'];
			}

			$wpdb->insert('bill_config', [
				'config' => 'product_config', 'value' => json_encode($config)
			], ['%s', '%s']);
		}

		$product_config = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "product_config"');

		if (isset($product_config->value) && strlen($product_config->value) > 3) {
			$product_config = json_decode($product_config->value);
		}

		$this->product_config = $product_config;
	}

	public function syncStock()
	{
		if (!$this->checkToken('key_stock_cron')) {
			return false;
		}

		$this->updateStockConfig();
		$stock_config = $this->getStockConfig();
		if ($stock_config->sincronizar_stock != "1") {
			return false;
		}
		$time = $this->api->getServerTime();
		$server_time = $time->time;
		$this->doStockUpdate();
		$this->updateLastSyncTime('last_stock_sync', $server_time);
		$this->updateStockFromDatabase();
	}

	public function doStockUpdate()
	{
		$start = $this->getLastSyncTime('last_stock_sync');
		$data = date('Y-m-d H:i:s', 0);

		if ($start > 0 && !isset($_GET['update_all'])) {
			$data = $this->getPortugalDateTime($start);
		}

		$this->next_page = 1;
		$stock_config = $this->getStockConfig();
		$pesquisa = ['movimentos_desde' => $data];

		if (isset($stock_config->loja_stock) && is_numeric($stock_config->loja_stock) && $stock_config->loja_stock > 0) {
			$pesquisa['lojas'][] = $stock_config->loja_stock;
		}

		do {
			$pesquisa['page'] = $this->next_page;
			$stock =  $this->api->getStock($pesquisa);
			$this->saveStockInDatabase($stock);
			$this->next_page = $this->getNextPage($stock);
		} while ($this->next_page != null);
	}

	public function getNextPage($data)
	{
		return $data->current_page < $data->last_page ? $data->current_page + 1 : null;
	}

	private function getConfigValue($column)
	{
		global $wpdb;
		$config = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "' . $column . '"');

		if (isset($config->value)) {
			return $config->value;
		}

		return "";
	}

	public function deleteConfigValue($column)
	{
		global $wpdb;
		$wpdb->delete(
			'bill_config',
			array('config' => $column)
		);
	}

	private function updateConfigValue($column, $value)
	{
		global $wpdb;
		$wpdb->delete(
			'bill_config',
			array('config' => $column)
		);

		$wpdb->insert('bill_config', [
			'config' => $column, 'value' => $value
		], ['%s', '%s']);
		return "";
	}

	public function getLastSyncTime($column)
	{
		global $wpdb;
		$last_sync = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "' . $column . '"');

		if (isset($last_sync->value)) {
			return $last_sync->value;
		}

		return "";
	}

	private function updateLastSyncTime($column, $server_time)
	{
		global $wpdb;
		$wpdb->delete(
			'bill_config',
			array('config' => $column)
		);

		$wpdb->insert('bill_config', [
			'config' => $column, 'value' => $server_time
		], ['%s', '%s']);
	}

	private function saveItemsInDatabase($items)
	{
		global $wpdb;
		foreach ($items->data as $key => $item) {
			$item->meta = isset($item->meta) ? $this->manipulateMeta($item) : new \stdClass();

			$wpdb->insert('bill_produtos_sync', [
				'date' => $this->getPortugalDateTime(time()), 'value' => json_encode($item)
			], ['%s', '%s']);
		}
	}

	public function manipulateMeta($item)
	{
		$m = new \stdClass();
		foreach ($item->meta as $meta) {
			$m->{$meta->meta_key} = $meta->meta_value;
		}
		return $m;
	}

	private function saveStockInDatabase($stock)
	{
		global $wpdb;
		$sku_id = $this->getSkuToID();
		foreach ($stock->data as $product) {
			$sku = $product->item->codigo;
			if (isset($sku_id->{$sku})) {
				$wpdb->insert('bill_stock_sync', [
					'date' => $this->getPortugalDateTime(time()), 'value' => json_encode([
						'id' => $sku_id->{$sku},
						'stock_quantity' => $product->stock,
					])
				], ['%s', '%s']);
			}
		}
	}

	public function updateStockFromDatabase()
	{
		global $wpdb;
		$message['success'] = "";
		$message['error'] = "";
		$stock = $wpdb->get_results('SELECT * FROM bill_stock_sync');
		foreach ($stock as $p) {
			$data = json_decode($p->value);
			$exists = $this->product_exists($data->id);
			if ($exists == null) {
				$wpdb->delete('bill_stock_sync', array('id' => $p->id));
				$message['error'] .= "Don't exist ID $data->id<br>";
				continue;
			}
			$product = new WC_Product($data->id);
			$product->set_manage_stock(true);
			$product->set_stock_quantity($data->stock_quantity);
			if ($data->stock_quantity < 1) {
				$product->set_stock_status('outofstock');
			}
			$product->save();
			$message['success'] .= "Update Product $data->id - Stock: $data->stock_quantity <br>";
			$wpdb->delete('bill_stock_sync', array('id' => $p->id));
		}

		$this->insertLog('stock', $message);
	}

	public function totalItemsWaiting()
	{
		global $wpdb;
		return $wpdb->get_var('SELECT COUNT(*) FROM bill_produtos_sync');
	}

	public function generateCSVFilesFromDatabase()
	{
		global $wpdb;
		$product_config = $this->getProductConfig();
		$product_config->modo_de_importacao = isset($product_config->modo_de_importacao) ? $product_config->modo_de_importacao : "2";

		$produtos = $wpdb->get_results('SELECT * FROM bill_produtos_sync ORDER BY id ASC LIMIT 0,20');

		$new = $update = [];
		$this->updateCustomMap();
		$this->handleBillCategories();
		$sku_field = $this->getSKUField();
		
		foreach ($produtos as $p) {
			$data = json_decode($p->value);

			$sku = $data->codigo;
			if ($sku_field != "codigo") {
				$sku = isset($data->meta->{$sku_field}) && strlen($data->meta->{$sku_field}) > 1 ?  $data->meta->{$sku_field} : $data->codigo;
			}

			$product = $this->get_product_by_sku($sku);

			if ($product == null) {
				$new[] = $data;
			} else {
				$update[] = $data;
			}

			$wpdb->delete('bill_produtos_sync', array('id' => $p->id));
		}
		$this->createCSV($new, 'new_products');
		$this->createCSV($update, 'update_products');
	}

	public function handleBillCategories()
	{
		$categories = $this->api->getItemCategories();
		
		if( $categories == [] ){
			$this->categories = null;
			return null;
		}

		foreach($categories as $category){
			$this->categories[$category->id] = $category;
		}

		foreach($categories as $category){
			$current_category_id = $category->id;
			$this->categories[$current_category_id]->{"categoria"} = "";

			$this->categories[$current_category_id]->{"categoria"} = $category->nome;
	
			if( $category->categoria_pai_id == NULL || $category->categoria_pai_id == $category->id){ 
				continue; #no parent
			}

			$this->generateWooCategoryNameStyle($current_category_id,$category);
		}
	}

	public function generateWooCategoryNameStyle($current_category_id, $category)
	{		
		foreach( $this->categories as $cat){
			if( $category->categoria_pai_id == $cat->id ){
				$this->categories[$current_category_id]->{"categoria"} =  $cat->nome . ">" . $this->categories[$current_category_id]->{"categoria"};
				
				if( $cat->categoria_pai_id != null){ #se tiver pai
					$this->generateWooCategoryNameStyle($current_category_id, $cat);
				}
				break;
			}
		}
	}

	public function getSKUField()
	{
		$field = 'codigo';
		if ($this->product_config->modo_de_importacao == "1") {
			if (isset($this->custom_map->SKU)) {
				return $this->custom_map->SKU;
			}
		}

		return $field;
	}

	public function getCurrentMap()
	{
		if ($this->product_config->modo_de_importacao == "1") {
			return $this->custom_map;
		}

		return $this->map;
	}

	public function getNameField()
	{
		$field = 'descricao';
		if ($this->product_config->modo_de_importacao == "1") {
			if (isset($this->custom_map->Name)) {
				return $this->custom_map->Name;
			}
		}

		return $field;
	}

	protected function createCSV($data, $file_name)
	{
		if (empty($data)) {
			return true;
		}

		$file_name = __DIR__ . '/import/' .  basename($file_name) .  time() . '.csv';
		$fh = fopen($file_name, 'w+');
		$data = $this->getWooCSVDataFormat($data);
		foreach ($data as $row) {
			fputcsv($fh, $row, ",", '"');
		}
		fclose($fh);
		$title = $this->generateTitles();
		$this->prepend($title, $file_name);
	}

	public function generateTitles()
	{
		$titles = "";
	 	$map = $this->map;
		foreach (array_flip($map) as $str) {
			if ($titles != "") {
				$titles .= ",";
			}
			if (strpos($str, ' ') !== false) {
				$titles .= '"' . $str . '"';
			} else {
				$titles .= $str;
			}
		}
		return $titles . PHP_EOL;
	}

	protected function prepend($string, $orig_filename)
	{
		$context = stream_context_create();
		$orig_file = fopen($orig_filename, 'r', 1, $context);

		$temp_filename = tempnam(sys_get_temp_dir(), 'php_prepend_');
		file_put_contents($temp_filename, $string);
		file_put_contents($temp_filename, $orig_file, FILE_APPEND);

		fclose($orig_file);
		unlink($orig_filename);
		rename($temp_filename, $orig_filename);
	}

	public function getWooCSVDataFormat($data)
	{
		$new_data = [];
		$sku_field = $this->getSKUField();
		$name_field = $this->getNameField();
		$map = $this->getCurrentMap();
		$has_id = false;
		foreach ($data as $key => $d) {
			#SKU - text
			$new_data[$key]['SKU'] = isset($d->codigo) ? $d->codigo : '';
			if ($sku_field != "codigo") {
				$new_data[$key]['SKU'] = isset($d->meta->{$sku_field}) && strlen($d->meta->{$sku_field}) > 1 ?  $d->meta->{$sku_field} : $d->codigo;
			}

			#NAME - text
			$new_data[$key]['Name'] = isset($d->descricao) ? $d->descricao : '';
			if ($name_field != "descricao") {
				$new_data[$key]['Name'] = isset($d->meta->{$name_field}) && strlen($d->meta->{$name_field}) > 1 ?  $d->meta->{$name_field} : $d->descricao;
			}

			$new_data[$key]['Images'] = $this->getWooMetaImages($d);
			$new_data[$key]['Categories'] = $this->getCategoriesWooFormat($d);
			$new_data[$key]['Regular price'] = $this->getRegularPrice($d);
			$new_data[$key] = $this->getAttributesWooFormat($new_data[$key], $d);

			$product = $this->get_product_by_sku($new_data[$key]['SKU']);

			if( $product != null){
				$new_data[$key]['ID'] = $product;
				$has_id = true;
				$this->map['ID'] = 'id'; 
			}

			foreach ($d->meta as $meta_key => $meta_value) {				
				if (!in_array($meta_key, (array) $map)) {
					continue;
				}

				if (in_array($meta_key, $this->exclude_fields)) {
					continue;
				}

				if (in_array($meta_key, $this->cast_integer)) {
					$meta_value = (int) $meta_value;
				}

				$woo_key = \array_search( $meta_key, (array) $map );
				if (
					$woo_key == 'SKU'
					&& $new_data[$key]['SKU'] != ''
				) {
					continue;
				}

				if (
					$woo_key == 'Name'
					&& $new_data[$key]['Name'] != ''
				) {
					continue;
				}

				$new_data[$key][$woo_key] = $meta_value;
			}
		}

		foreach ($new_data as $index => $product) {
			foreach ($this->map as $meta_key => $field) {
				$structure[$index][$meta_key] = isset($product[$meta_key]) ? $product[$meta_key] : '';
				if (
					isset($this->default_values[$meta_key])
					&& $structure[$index][$meta_key] == ""
				) {
					$structure[$index][$meta_key] = $this->default_values[$meta_key];
				}
			}
		}
		return $structure;
	}

	private function getWooMetaImages($data)
	{
		$images = [];
		foreach ($data->meta as $meta_key => $meta_value) {
			if (strpos($meta_key, 'image') !== false 
			|| strpos($meta_key, 'Image') !== false) {
				if (strlen($meta_value) > 10 
				&& strpos($meta_value, '.') !== false) {
					$images[] =  $this->api->getUrl() . "utilizador/imagem/item/" . $meta_value;
				}
			}
		}
		if (!empty($images)) {
			$images = implode(', ', $images);
		} else {
			$images = "";
		}
		return $images;
	}

	private function getRegularPrice($data)
	{
		$price = "";

		if( isset($data->precos[0]) ){
			$price = number_format($data->precos[0]->preco_com_iva,2,'.', '');
			if( $price == 0){
				$price = "";
			}
		}

		return $price;
	}

	private function getCategoriesWooFormat($data)
	{
		$categories = [];
		if( $data->categorias == NULL){
			return $categories = "";
		}
	
		foreach ($data->categorias as $category) {
			$categories[] = $this->categories[$category->id]->categoria;
		}
		if (!empty($categories)) {
			$categories = implode(', ', $categories);
		} else {
			$categories = "";
		}
		return $categories;
	}

	public function getAttributesWooFormat($data, $product)
	{
		foreach ($product->meta as $meta_key => $meta_value) {
			if (strpos($meta_key, 'Attribute ') !== false 
			|| strpos($meta_key, 'attribute ') !== false) {
				$name = $meta_key . ' Name';
				$value = $meta_key . ' Value';
				$clean_key = str_replace(['Attribute ', 'attribute'], ['', ''], $meta_key);
				$data[$name] = $clean_key;
				$data[$value] = $meta_value;
				$this->map[$name] = 'attributes:name' .  str_replace(' ', '', strtolower($clean_key));
				$this->map[$value] = 'attributes:value' .  str_replace(' ', '', strtolower($clean_key));
			}
		}

		return $data;
	}

	public function importUpdateProducts()
	{
		$this->parseNewFiles();
		$this->parseUpdateFiles();
		$this->updateSkuToID();
	}

	function handleResultsProductSync($results)
	{
		$message['success'] = "";
		$message['error'] = "";

		if (!empty($results['imported'])) {
			$message['success'] .= " Imported: ";
			foreach ($results['imported'] as $imported) {
				$message['success'] .=  $imported . ",";
			}
		}

		if (!empty($results['updated'])) {
			$message['success'] .= " Updated: ";
			foreach ($results['updated'] as $updated) {
				$message['success'] .=  $updated . ",";
			}
		}

		if (!empty($results['failed'])) {
			$message['error'] .= " Failed: ";
			foreach ($results['failed'] as $error) {
				$message['error'] .=  $error->get_error_message() . " - ";
				$message['error'] .=  implode(',', $error->get_error_data()) . "<br>";
			}
		}

		if (!empty($results['skipped'])) {
			$message['error'] .= " Skipped: ";
			foreach ($results['skipped'] as $error) {
				$message['error'] .=  $error->get_error_message() . " - ";
				$message['error'] .= implode(',', $error->get_error_data()) . "<br>";
			}
		}

		return $message;
	}

	protected function parseNewFiles()
	{
		$files = glob(__DIR__ . "/import/new_products*.csv");
		foreach ($files as $file) {
			$args = array(
				'mapping'          => $this->map,
				'parse'            => true,
				'prevent_timeouts' => false
			);

			$importer = new WC_Product_CSV_Importer($file, $args);
			$results  = $importer->import();
			$this->insertLog('product', $this->handleResultsProductSync($results));
			unlink($file);
		}
	}

	protected function parseUpdateFiles()
	{
		$files = glob(__DIR__ . "/import/update_products*.csv");
		$this->map['ID'] = 'id'; 
		foreach ($files as $file) {
			$args = array(
				'mapping'          => $this->map,
				'parse'            => true,
				'prevent_timeouts' => false,
				'update_existing' => true
			);

			$importer = new WC_Product_CSV_Importer($file, $args);
			$results  = $importer->import();
			$this->insertLog('product', $this->handleResultsProductSync($results));
			unlink($file);
		}
	}

	private function get_product_by_sku($sku)
	{
		global $wpdb;

		$product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));

		if ($product_id) return $product_id;

		return null;
	}


	protected function product_exists($id)
	{
		global $wpdb;

		$query = "SELECT ID FROM $wpdb->posts WHERE";
		$args = array();
		$query .= " id = '%s' ";
		$args[] = $id;
		$query .= " AND (post_type = '%s' OR post_type = '%s')";
		$args[] = 'product';
		$args[] = 'product_variation';
		return $wpdb->get_var($wpdb->prepare($query, $args));
	}

	public function checkIfWooMetaExist($type)
	{
		$structure = $this->api->getItemsMetaStructure();
		$this->verifyMeta($structure, $type);
	}

	public function getItemMetaStructurePlucked()
	{
		$structure = $this->api->getItemsMetaStructure();
		return $this->array_pluck($structure, 'meta_key');
	}

	public function verifyMeta($structure, $type)
	{
		$current_meta_keys = $this->array_pluck($structure, 'meta_key');
		$format = ($type == 'all') ? $this->woo_format : $this->simple_format;
		foreach ($format as $woo_meta_key => $type) {
			if (!in_array($woo_meta_key, $current_meta_keys)) {
				$this->api->createItemsMetaStructure([
					'meta_key' => $woo_meta_key,
					'meta_value' => $type
				]);
			}
		}
	}

	protected function array_pluck($array, $key)
	{
		return array_map(function ($v) use ($key) {
			return is_object($v) ? $v->$key : $v[$key];
		}, $array);
	}

	public function updateCustomMap()
	{
		global $wpdb;
		if (isset($_POST['update_custom_map'])) {
			$_POST['custom_map'] = array_filter($_POST['custom_map']);

			$wpdb->delete('bill_config', array('config' => 'custom_map'));

			$custom_map = [];

			if (isset($_POST['custom_map'])) {
				$custom_map = array_map('strip_tags', $_POST['custom_map']);
			}

			$wpdb->insert('bill_config', [
				'config' => 'custom_map', 'value' => json_encode($custom_map)
			], ['%s', '%s']);
		}

		$custom_map = $wpdb->get_row('SELECT * FROM bill_config WHERE config = "custom_map"');

		if (isset($custom_map->value) && strlen($custom_map->value) > 3) {
			$custom_map = json_decode($custom_map->value);
		}

		$this->custom_map = $custom_map;
	}

	public function getMap()
	{
		return $this->map;
	}

	public function getCustomMap()
	{
		return $this->custom_map;
	}

	public function getSimpleMap()
	{
		return $this->simple_map;
	}

	public function getMandatory()
	{
		return $this->mandatory;
	}
}
