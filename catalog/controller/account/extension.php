<?php
class ControllerAccountExtension extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/extension', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/extension');

		$this->getList();
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/extension', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/extension');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_extension->addExtension($this->request->post);
			
			$this->session->data['success'] = $data['text_add'];

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				$activity_data = array(
					'seller_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);

				$this->model_account_activity->addActivity('extension_add', $activity_data);
			}

			$this->response->redirect($this->url->link('account/extension', '', true));
		}

		$this->getForm();
	}

	public function edit() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/extension', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/extension');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_extension->editExtension($this->request->get['extension_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_edit');

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('account/activity');

				$activity_data = array(
					'seller_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);

				$this->model_account_activity->addActivity('extension_edit', $activity_data);
			}

			$this->response->redirect($this->url->link('account/extension', '', true));
		}

		$this->getForm();
	}

	protected function getList() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/extension'));
		
		$data['breadcrumbs'] = array();    
		$data['breadcrumbs'][] = array(
			'text' => $data['text_home'],
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_account'],
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			'href' => $this->url->link('account/extension', '', true)
		);
		
		$data['success']		= isset($this->session->data['success']) ? $this->session->data['success'] : '';
		$data['error_warning'] 	= isset($this->error['warning']) ? $this->error['warning'] : '';

		$this->load->model('account/sale');
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$data['extensions'] = array();
		
		$filter_data = array(
			'sort'  => 'name',
			'order' => 'ASC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		
		$results = $this->model_account_extension->getExtensions($filter_data);
		foreach ($results as $result) {
			$data['extensions'][] = array(
				'extension_id' 	=> $result['extension_id'],
				'name'    		=> $result['name'],
				'sales'    		=> $this->model_account_sale->getTotalSalesByExtension($result['extension_id']),
				'total'    		=> $this->currency->format($this->model_account_sale->getTotalAmountByExtension($result['extension_id']), $this->config->get('config_currency')),
				'status'     	=> $result['status'],
				'approved'     	=> $result['approved'],
				'date_added'    => date($data['date_format_short'], strtotime($result['date_added'])),
				'view'     		=> $this->url->link('marketplace/extension', 'extension_id=' . $result['extension_id'], true),
				'edit'     		=> $this->url->link('account/extension/edit', 'extension_id=' . $result['extension_id'], true)
			);
		}
		
		$extension_total 		= $this->model_account_extension->getTotalExtensions();
		
		$pagination 			= new Pagination();
		$pagination->total 		= $extension_total;
		$pagination->page 		= $page;
		$pagination->limit 		= 10;
		$pagination->url 		= $this->url->link('account/extension', 'page={page}', true);

		$data['pagination'] 	= $pagination->render();

		$data['results'] 		= sprintf($data['text_pagination'], ($extension_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($extension_total - 10)) ? $extension_total : ((($page - 1) * 10) + 10), $extension_total, ceil($extension_total / 10));

		$data['add'] 			= $this->url->link('account/extension/add', '', true);
		$data['continue'] 		= $this->url->link('account/account', '', true);

		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/extension_list', $data));
	}

	protected function getForm() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/extension'));
    
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $data['text_home'],
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_account'],
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			'href' => $this->url->link('account/extension', '', true)
		);

		if (!empty($this->request->get['extension_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $data['text_edit_extension'],
				'href' => $this->url->link('account/extension/add', '', true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $data['text_edit_extension'],
				'href' => $this->url->link('account/extension/edit', '', true)
			);
		}
		
		if (!empty($this->error)) {
			foreach ($this->error as $key => $value) {
				$data['error_' . $key] = $value;
			}
		}
		unset($this->error);
		
		if (!isset($this->request->get['extension_id'])) {
			$data['action'] = $this->url->link('account/extension/add', '', true);
		} else {
			$data['action'] = $this->url->link('account/extension/edit', 'extension_id=' . $this->request->get['extension_id'], true);
		}

		if (isset($this->request->get['extension_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$extension_info = $this->model_account_extension->getExtension($this->request->get['extension_id']);
		}
		
		$fields = array(
			'name',
			'category_id',
			'description',
			'documentation',
			'changelog',
			'license',
			'license_period',
			'price',
			'price_renew',
			'demo_catalog',
			'demo_admin',
			'demo_user',
			'demo_pass',
			'image',
			'banner',
			'images',
			'downloads',
			'ga_tracking',
			'tag',
			'status',
			'apprroved',
			'update',
		);
		
		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} elseif (!empty($extension_info[$field])) {
				$data[$field] = $extension_info[$field];
			} else {
				$data[$field] = '';
			}
		}
		
		$data['approved']			= !empty($extension_info['approved']) ? true : false;
		
		$this->load->model('catalog/category');
		$data['categories'] 		= $this->model_catalog_category->getCategories(0);
		
		$data['versions'] 			= explode(',', $this->config->get('config_extension_versions'));
		$data['license_types'] 		= explode(',', $this->config->get('config_extension_license_types'));
		$data['license_periods']	= explode(',', $this->config->get('config_extension_license_periods'));

		$data['back'] 				= $this->url->link('account/extension', '', true);

		$data['column_left'] 		= $this->load->controller('common/column_left');
		$data['column_right'] 		= $this->load->controller('common/column_right');
		$data['content_top'] 		= $this->load->controller('common/content_top');
		$data['content_bottom'] 	= $this->load->controller('common/content_bottom');
		$data['footer'] 			= $this->load->controller('common/footer');
		$data['header'] 			= $this->load->controller('common/header');


		$this->response->setOutput($this->load->view('account/extension_form', $data));
	}

	protected function validateForm() {
		$this->load->language('account/extension');
		
		if ((utf8_strlen(trim($this->request->post['name'])) < 1) || (utf8_strlen(trim($this->request->post['name'])) > $this->config->get('config_extension_name'))) {
			$this->error['name'] = sprintf($this->language->get('text_error_name'), $this->config->get('config_extension_name'));
		}
		
		if ((utf8_strlen(trim($this->request->post['description'])) < 1) || (utf8_strlen(trim($this->request->post['description'])) > $this->config->get('config_extension_description'))) {
			$this->error['description'] = sprintf($this->language->get('text_error_description'), $this->config->get('config_extension_description'));
		}
		
		if (utf8_strlen(trim($this->request->post['documentation'])) > $this->config->get('config_extension_documentation')) {
			$this->error['documentation'] = sprintf($this->language->get('text_error_documentation'), $this->config->get('config_extension_documentation'));
		}
		
		if (utf8_strlen(trim($this->request->post['changelog'])) > $this->config->get('config_extension_changelog')) {
			$this->error['changelog'] = sprintf($this->language->get('text_error_changelog'), $this->config->get('config_extension_changelog'));
		}
		
		if ($this->request->post['price'] < $this->config->get('config_extension_price')) {
			$this->error['price'] = sprintf($this->language->get('text_error_price'), $this->currency->format($this->config->get('config_extension_price'), $this->config->get('config_currency')));
		}
		
		if ($this->request->post['price_renew'] < 0) {
			$this->error['price_renew'] = $this->language->get('text_error_price_renew');
		}
		
		if (empty($this->request->post['download'])) {
			$this->error['download'] = $this->language->get('text_error_download');
		}
		
		$tags = explode(',', $this->request->post['tag']);
		if (count($tags) > $this->config->get('config_extension_tag_count')) {
			$this->error['tag'] = sprintf($this->language->get('text_error_tag_count'), $this->config->get('config_extension_tag_count'));
		} else {
			foreach ($tags as $tag) {
				if ((utf8_strlen(trim($tag)) < 1) || (utf8_strlen(trim($tag)) > $this->config->get('config_extension_tag_char'))) {
					$this->error['tag'] = sprintf($this->language->get('text_error_tag_char'), $this->config->get('config_extension_tag_char'));
					break;
				}
			}
		}
		
		if ($this->error) {
			$this->error['warning'] = $this->language->get('text_error_warning');
		}

		return !$this->error;
	}
}