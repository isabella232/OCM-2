<?php
class ControllerSettingSetting extends Controller {
	private $error = array();

	public function index() {
		$data = array();
		$data = array_merge($data, $this->load->language('setting/setting'));
		
		$this->document->setTitle($data['heading_title']);

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('config', $this->request->post);

			if ($this->config->get('config_currency_auto')) {
				$this->load->model('localisation/currency');

				$this->model_localisation_currency->refresh();
			}

			$this->session->data['success'] = $data['text_success'];

			$this->response->redirect($this->url->link('setting/store', 'token=' . $this->session->data['token'], true));
		}
		
		$data['success']		= isset($this->session->data['success']) ? $this->session->data['success'] : '';
		unset($this->session->data['success']);
		
		if (!empty($this->error)) {
			foreach ($this->error as $key => $value) {
				$data['error_' . $key] = $value;
			}
		}
		unset($this->error);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $data['text_home'],
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_stores'],
			'href' => $this->url->link('setting/store', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			'href' => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('setting/setting', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);
		$data['token'] = $this->session->data['token'];
		
		$fields = array(
			'meta_title',
			'meta_description',
			'meta_keyword',
			'theme',
			'layout_id',
			'name',
			'owner',
			'address',
			'geocode',
			'email',
			'telephone',
			'image',
			'open',
			'comment',
			'location',
			'country_id',
			'zone_id',
			'language',
			'admin_language',
			'currency',
			'currency_auto',
			'limit_admin',
			'product_count',
			'review_status',
			'review_guest',
			'tax',
			'tax_default',
			'tax_customer',
			'customer_online',
			'customer_activity',
			'customer_search',
			'customer_group_id',
			'customer_group_display',
			'customer_price',
			'login_attempts',
			'account_id',
			'checkout_id',
			'invoice_prefix',
			'order_status_id',
			'processing_status',
			'complete_status',
			'fraud_status_id',
			'api_id',
			'affiliate_approval',
			'affiliate_auto',
			'affiliate_commission',
			'affiliate_id',
			'captcha',
			'captcha_page',
			'logo',
			'icon',
			'ftp_hostname',
			'ftp_port',
			'ftp_username',
			'ftp_password',
			'ftp_root',
			'ftp_status',
			'mail_protocol',
			'mail_parameter',
			'mail_smtp_hostname',
			'mail_smtp_username',
			'mail_smtp_password',
			'mail_smtp_port',
			'mail_smtp_timeout',
			'mail_alert',
			'alert_email',
			'secure',
			'shared',
			'robots',
			'seo_url',
			'file_max_size',
			'file_ext_allowed',
			'file_mime_allowed',
			'maintenance',
			'password',
			'encryption',
			'compression',
			'error_display',
			'error_log',
			'error_filename',
			'extension_versions',
			'extension_license_types',
			'extension_license_periods',
			'extension_name',
			'extension_description',
			'extension_documentation',
			'extension_changelog',
			'extension_price',
			'extension_tag_count',
			'extension_tag_char',
		);

		foreach ($fields as $field) {
			if (isset($this->request->post['config_' . $field])) {
				$data['config_' . $field] = $this->request->post['config_' . $field];
			} else {
				$data['config_' . $field] = $this->config->get('config_' . $field);
			}
		}

		if ($this->request->server['HTTPS']) {
			$data['store_url'] = HTTPS_CATALOG;
		} else {
			$data['store_url'] = HTTP_CATALOG;
		}

		$data['themes'] = array();
		$this->load->model('extension/extension');
		$extensions = $this->model_extension_extension->getInstalled('theme');
		foreach ($extensions as $code) {
			$this->load->language('extension/theme/' . $code);
			$data['themes'][] = array(
				'text'  => $this->language->get('heading_title'),
				'value' => $code
			);
		}
			
		$this->load->model('design/layout');
		$data['layouts'] = $this->model_design_layout->getLayouts();

		$this->load->model('tool/image');
		if (isset($this->request->post['config_image']) && is_file(DIR_IMAGE . $this->request->post['config_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['config_image'], 100, 100);
		} elseif ($this->config->get('config_image') && is_file(DIR_IMAGE . $this->config->get('config_image'))) {
			$data['thumb'] = $this->model_tool_image->resize($this->config->get('config_image'), 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/currency');
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('catalog/information');
		$data['informations'] = $this->model_catalog_information->getInformations();

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('user/api');
		$data['apis'] = $this->model_user_api->getApis();

		$this->load->model('extension/extension');
		$data['captchas'] = array();

		// Get a list of installed captchas
		$extensions = $this->model_extension_extension->getInstalled('captcha');
		foreach ($extensions as $code) {
			$this->load->language('extension/captcha/' . $code);
			if ($this->config->get($code . '_status')) {
				$data['captchas'][] = array(
					'text'  => $this->language->get('heading_title'),
					'value' => $code
				);
			}
		}

		$data['captcha_pages'] = array();
		$data['captcha_pages'][] = array(
			'text'  => $this->language->get('text_register'),
			'value' => 'register'
		);
		$data['captcha_pages'][] = array(
			'text'  => $this->language->get('text_extension'),
			'value' => 'extension'
		);
		$data['captcha_pages'][] = array(
			'text'  => $this->language->get('text_review'),
			'value' => 'review'
		);
		$data['captcha_pages'][] = array(
			'text'  => $this->language->get('text_contact'),
			'value' => 'contact'
		);

		if (isset($this->request->post['config_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($this->request->post['config_logo'], 100, 100);
		} elseif ($this->config->get('config_logo') && is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), 100, 100);
		} else {
			$data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		if (isset($this->request->post['config_icon']) && is_file(DIR_IMAGE . $this->request->post['config_icon'])) {
			$data['icon'] = $this->model_tool_image->resize($this->request->post['config_icon'], 100, 100);
		} elseif ($this->config->get('config_icon') && is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $this->model_tool_image->resize($this->config->get('config_icon'), 100, 100);
		} else {
			$data['icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['mail_alerts'] = array();
		$data['mail_alerts'][] = array(
			'text'  => $this->language->get('text_mail_account'),
			'value' => 'account'
		);
		$data['mail_alerts'][] = array(
			'text'  => $this->language->get('text_mail_affiliate'),
			'value' => 'affiliate'
		);
		$data['mail_alerts'][] = array(
			'text'  => $this->language->get('text_mail_order'),
			'value' => 'order'
		);
		$data['mail_alerts'][] = array(
			'text'  => $this->language->get('text_mail_review'),
			'value' => 'review'
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/setting', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('text_error_permission');
		}

		if (!$this->request->post['config_meta_title']) {
			$this->error['meta_title'] = $this->language->get('text_error_meta_title');
		}

		if (!$this->request->post['config_name']) {
			$this->error['name'] = $this->language->get('text_error_name');
		}

		if ((utf8_strlen($this->request->post['config_owner']) < 3) || (utf8_strlen($this->request->post['config_owner']) > 64)) {
			$this->error['owner'] = $this->language->get('text_error_owner');
		}

		if ((utf8_strlen($this->request->post['config_address']) < 3) || (utf8_strlen($this->request->post['config_address']) > 256)) {
			$this->error['address'] = $this->language->get('text_error_address');
		}

		if ((utf8_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('text_error_email');
		}

		if ((utf8_strlen($this->request->post['config_telephone']) < 3) || (utf8_strlen($this->request->post['config_telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('text_error_telephone');
		}

		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$this->error['customer_group_display'] = $this->language->get('text_error_customer_group_display');
		}

		if (!$this->request->post['config_limit_admin']) {
			$this->error['limit_admin'] = $this->language->get('text_error_limit');
		}

		if ($this->request->post['config_login_attempts'] < 1) {
			$this->error['login_attempts'] = $this->language->get('text_error_login_attempts');
		}

		if (!isset($this->request->post['config_processing_status'])) {
			$this->error['processing_status'] = $this->language->get('text_error_processing_status');
		}

		if (!isset($this->request->post['config_complete_status'])) {
			$this->error['complete_status'] = $this->language->get('text_error_complete_status');
		}

		if ($this->request->post['config_ftp_status']) {
			if (!$this->request->post['config_ftp_hostname']) {
				$this->error['ftp_hostname'] = $this->language->get('text_error_ftp_hostname');
			}

			if (!$this->request->post['config_ftp_port']) {
				$this->error['ftp_port'] = $this->language->get('text_error_ftp_port');
			}

			if (!$this->request->post['config_ftp_username']) {
				$this->error['ftp_username'] = $this->language->get('text_error_ftp_username');
			}

			if (!$this->request->post['config_ftp_password']) {
				$this->error['ftp_password'] = $this->language->get('text_error_ftp_password');
			}
		}

		if (!$this->request->post['config_error_filename']) {
			$this->error['error_filename'] = $this->language->get('text_error_error_filename');
		} else {
			if (preg_match('/\.\.[\/\\\]?/', $this->request->post['config_error_filename'])) {
				$this->error['error_filename'] = $this->language->get('text_error_malformed_filename');
			}
		}

		if ((utf8_strlen($this->request->post['config_encryption']) < 32) || (utf8_strlen($this->request->post['config_encryption']) > 1024)) {
			$this->error['encryption'] = $this->language->get('text_error_encryption');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('text_error_warning');
		}

		return !$this->error;
	}
	
	public function theme() {
		if ($this->request->server['HTTPS']) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}
		
		// This is only here for compatibility with old themes.
		if ($this->request->get['theme'] == 'theme_default') {
			$theme = $this->config->get('theme_default_directory');
		} else {
			$theme = basename($this->request->get['theme']);
		}
		
		if (is_file(DIR_CATALOG . 'view/theme/' . $theme . '/image/' . $theme . '.png')) {
			$this->response->setOutput($server . 'catalog/view/theme/' . $theme . '/image/' . $theme . '.png');
		} else {
			$this->response->setOutput($server . 'image/no_image.png');
		}
	}	
}
