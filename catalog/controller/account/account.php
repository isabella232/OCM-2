<?php
class ControllerAccountAccount extends Controller {
	public function index() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/account'));
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
		
		$this->load->language('account/account');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['success']		= isset($this->session->data['success']) ? $this->session->data['success'] : '';

		$data['edit'] 			= $this->url->link('account/edit', '', true);
		$data['password'] 		= $this->url->link('account/password', '', true);
		
		$data['order'] 			= $this->url->link('account/order', '', true);
		$data['download'] 		= $this->url->link('account/download', '', true);
		
		$data['extension'] 		= $this->url->link('account/extension', '', true);
		
		$data['seller'] 		= $this->url->link('account/seller', '', true);
		$data['sale'] 			= $this->url->link('account/sale', '', true);
		$data['transaction'] 	= $this->url->link('account/transaction', '', true);
		
		$data['developer_status'] = ($this->customer->getGroupId()) ? true : false;
		
		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('account/account', $data));
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
