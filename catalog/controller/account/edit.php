<?php
class ControllerAccountEdit extends Controller {
	private $error = array();

	public function index() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/edit'));
		
		if (!$this->customer->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->session->data['redirect'] = $this->url->link('account/edit', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_account_customer->editCustomer($this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$this->response->redirect($this->url->link('account/account', 'token=' . $this->session->data['token'], true));
		}

		$this->document->setTitle($data['heading_title']);
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text'      => $data['text_home'],
			'href'      => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $data['text_account'],
			'href'      => $this->url->link('account/account', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text'      => $data['text_edit'],
			'href'      => $this->url->link('account/edit', 'token=' . $this->session->data['token'], true)
		);

		if (!empty($this->error)) {
			foreach ($this->error as $key => $value) {
				$data['error_' . $key] = $value;
			}
		}
		unset($this->error);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		}
		
		$fields = array(
			'username',
			'firstname',
			'lastname',
			'email',
			'image',
			'telephone',
			'company',
			'address_1',
			'address_2',
			'city',
			'postcode',
			'country_id',
			'zone_id',
			'newsletter',
			'extension',
		);
		
		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} elseif (!empty($customer_info)) {
				$data[$field] = $customer_info[$field];
			} else {
				$data[$field] = '';
			}
		}
		
		$this->load->model('localisation/country');
		$data['countries'] 		= $this->model_localisation_country->getCountries();

		$data['action'] 		= $this->url->link('account/edit', 'token=' . $this->session->data['token'], true);
		
		$data['back'] 			= $this->url->link('account/account', 'token=' . $this->session->data['token'], true);

		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/edit', $data));
	}

	protected function validate() {
		if ((utf8_strlen(trim($this->request->post['username'])) < 1) || (utf8_strlen(trim($this->request->post['username'])) > 32)) {
			$this->error['username'] = $this->language->get('text_error_username');
		}

		if (($this->customer->getUsername() != $this->request->post['username']) && $this->model_account_customer->getTotalCustomersByUsername($this->request->post['username'])) {
			$this->error['username'] = $this->language->get('text_error_username_exists');
		}
		
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('text_error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('text_error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('text_error_email');
		}

		if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('text_error_email_exists');
		}

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			$this->error['address_1'] = $this->language->get('text_error_address_1');
		}
		
		if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
			$this->error['city'] = $this->language->get('text_error_city');
		}
		
		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$this->error['postcode'] = $this->language->get('text_error_postcode');
		}
		
		if ($this->request->post['country_id'] == '' || !is_numeric($this->request->post['country_id'])) {
			$this->error['country'] = $this->language->get('text_error_country');
		}
		
		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
			$this->error['zone'] = $this->language->get('text_error_zone');
		}

		return !$this->error;
	}
}