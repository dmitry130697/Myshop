<?php
class ControllerCommonHeader extends Controller {
				
				/**
					*Ajax advanced search starts
					*/
				public function ajaxLiveSearch() {
					$json = array();
					if(!empty($this->request->get['filter_name'])){
						$this->load->model('catalog/product');
						$this->load->model('tool/image');
						
						$filter_mpn = ($this->config->get('config_ajaxadvancedsearch_mpn')) ? true : false;
						$filter_manufacturer = ($this->config->get('config_ajaxadvancedsearch_manufacturer_search')) ? true : false;
						$filter_isbn = ($this->config->get('config_ajaxadvancedsearch_isbn')) ? true : false;
						$filter_jan = ($this->config->get('config_ajaxadvancedsearch_jan')) ? true : false;
						$filter_ean = ($this->config->get('config_ajaxadvancedsearch_ean')) ? true : false;
						$filter_upc = ($this->config->get('config_ajaxadvancedsearch_upc')) ? true : false;
						$filter_sku = ($this->config->get('config_ajaxadvancedsearch_sku')) ? true : false;
						$filter_model = ($this->config->get('config_ajaxadvancedsearch_model_search')) ? true : false;
						$filter_tag = ($this->config->get('config_ajaxadvancedsearch_tag')) ? true : false;
						
						$filterdata=array(
							'filter_name' => $this->request->get['filter_name'],
							'filter_mpn' => $filter_mpn,
							'filter_manufacturer' => $filter_manufacturer,
							'filter_isbn' => $filter_isbn,
							'filter_jan' => $filter_jan,
							'filter_ean' => $filter_ean,
							'filter_upc' => $filter_upc,
							'filter_sku' => $filter_sku,
							'filter_model' => $filter_model,
							'filter_tag' => $filter_tag,
							'start' => 0,
							'limit' =>  ($this->config->get('config_ajaxadvancedsearch_limit')!=0) ? $this->config->get('config_ajaxadvancedsearch_limit') : 5,
						);
						$results = (array) $this->model_catalog_product->ajaxLiveSearch($filterdata);
						foreach($results as $result){
							$width = 100;
							$height = 100;
							if($this->config->get('config_ajaxadvancedsearch_image_width')!='' && $this->config->get('config_ajaxadvancedsearch_image_height')!=''){
								$width = $this->config->get('config_ajaxadvancedsearch_image_width');
								$height = $this->config->get('config_ajaxadvancedsearch_image_height');
							}
							if(!empty($result['image'])&&file_exists(DIR_IMAGE .$result['image'])){
								$image = $this->model_tool_image->resize($result['image'],$width,$height);
							}else if(file_exists(DIR_IMAGE .'data/logo.png')){
								$image = $this->model_tool_image->resize('data/logo.png',$width,$height);
							}else{	
								$image = $this->model_tool_image->resize('no_image.jpg',$width,$height);
							}
							// no highlight
							$highlight = false;							
							if($this->config->get('config_ajaxadvancedsearch_highlight')!='' && $this->config->get('config_ajaxadvancedsearch_highlight')==1){
								// seperate highlight
								$highlight = 'seperate';
							}else if($this->config->get('config_ajaxadvancedsearch_highlight')!='' && $this->config->get('config_ajaxadvancedsearch_highlight')==2){
								// combine highlight
								$highlight = 'combine';
							}
							$name='';
							$model='';
							$manufacturer='';
							
							if($highlight == 'seperate'){
								$breakchars = array_map('strtolower',array_unique(str_split(html_entity_decode ($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'))));
								foreach((array)str_split(html_entity_decode ($result['name'], ENT_QUOTES, 'UTF-8')) as $char){
									if(in_array(strtolower(strtoupper($char)),$breakchars)){
										$name.='<span class="highlight">'. htmlentities($char) .'</span>';
									}else{
										$name .= htmlentities($char);
									}
								}
								foreach((array)str_split(html_entity_decode ($result['model'], ENT_QUOTES, 'UTF-8')) as $char){
									if(in_array(strtolower(strtoupper($char)),$breakchars)){
										$model.='<span class="highlight">'. htmlentities($char) .'</span>';
									}else{
										$model .= htmlentities($char);
									}
								}
								
								foreach((array)str_split(html_entity_decode ($result['manufacturer'], ENT_QUOTES, 'UTF-8')) as $char){
									if(in_array(strtolower(strtoupper($char)),$breakchars)){
										$manufacturer.='<span class="highlight">'. htmlentities($char) .'</span>';
									}else{
										$manufacturer .= htmlentities($char);
									}
								}
							
							}else if($highlight == 'combine'){
								$breakchars = array();
								$result['name'] = html_entity_decode ($result['name'], ENT_QUOTES, 'UTF-8');
								$this->request->get['filter_name'] = html_entity_decode ($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8');
								$name=str_ireplace($this->request->get['filter_name'],'<span class="highlight">'. htmlentities(substr($result['name'],stripos($result['name'],$this->request->get['filter_name']),strlen($this->request->get['filter_name']))) .'</span>',$result['name']);
								
								$result['model'] = html_entity_decode ($result['model'], ENT_QUOTES, 'UTF-8');
								$model=str_ireplace($this->request->get['filter_name'],'<span class="highlight">'. htmlentities(substr($result['name'],stripos($result['name'],$this->request->get['filter_name']),strlen($this->request->get['filter_name']))) .'</span>',$result['model']);
								
								$result['manufacturer'] = html_entity_decode ($result['manufacturer'], ENT_QUOTES, 'UTF-8');
								$manufacturer=str_ireplace($this->request->get['filter_name'],'<span class="highlight">'. htmlentities(substr($result['name'],stripos($result['name'],$this->request->get['filter_name']),strlen($this->request->get['filter_name']))) .'</span>',$result['manufacturer']);
							}else{
								$breakchars = array();
								$name=$result['name'];
								$model=$result['model'];
								$manufacturer=$result['manufacturer'];
							}
							
							$json[] = array(
								'product_id' => $result['product_id'],
								'name' => $name,
								'name1' => $result['name'],
								'model' => ($this->config->get('config_ajaxadvancedsearch_model')) ? $model: false ,
								'stock_status' =>  ($this->config->get('config_ajaxadvancedsearch_stock')) ?  $result['stock_status'] : false,
								'image' => ($this->config->get('config_ajaxadvancedsearch_image')) ? $image : false,
								'manufacturer' => ($this->config->get('config_ajaxadvancedsearch_manufacturer')) ?  $manufacturer : false,
								'price' => ($this->config->get('config_ajaxadvancedsearch_price')) ? $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))) : false,
								'special' => ($this->config->get('config_ajaxadvancedsearch_price')) ? $result['special'] != 0.0000 ?  $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))) : 0 : false,
								'rating' => ($this->config->get('config_ajaxadvancedsearch_rating')) ? $result['rating'] : false,
								'reviews' => $result['reviews'],
							);
						}
					}
					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode($json));
				}
				/**
					*Ajax advanced search ends
					*/
			
	public function index() {
				
				/**
					*Ajax advanced search starts
					*/
					$this->language->load('common/header');
					$data['ajaxadvancedsearch_model'] = $this->language->get('ajaxadvancedsearch_model');
					$data['ajaxadvancedsearch_manufacturer'] = $this->language->get('ajaxadvancedsearch_manufacturer');
					$data['ajaxadvancedsearch_price'] = $this->language->get('ajaxadvancedsearch_price');
					$data['ajaxadvancedsearch_stock'] = $this->language->get('ajaxadvancedsearch_stock');
					$data['ajaxadvancedsearch_rating'] = $this->language->get('ajaxadvancedsearch_rating');
					
					$data['ajaxadvancesearch_imagewidth'] = 100;
					$data['ajaxadvancesearch_imageheight'] = 100;
					
					$data['ajaxadvancesearch_status'] = false;
					if($this->config->get('config_ajaxadvancedsearch')!='' && $this->config->get('config_ajaxadvancedsearch')==1){
						$data['ajaxadvancesearch_status'] =  $this->config->get('config_ajaxadvancedsearch');
					}
					if($this->config->get('config_ajaxadvancedsearch_image_width')!=''){
						$data['ajaxadvancesearch_imagewidth'] = $this->config->get('config_ajaxadvancedsearch_image_width');
					}
					if($this->config->get('config_ajaxadvancedsearch_image_height')!=''){
						$data['ajaxadvancesearch_imageheight'] = $this->config->get('config_ajaxadvancedsearch_image_height');
					}
					/**
					*Ajax advanced search ends
					*/
			
		// Analytics
		$this->load->model('extension/extension');

		$data['analytics'] = array();

		$analytics = $this->model_extension_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get($analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('analytics/' . $analytic['code']);
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}
