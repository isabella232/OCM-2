<?php
class ControllerAccountPassword extends Controller {
	private $error = array();

	public function index() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/password'));
		
		if (!$this->customer->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('account/customer');
			$this->model_account_customer->editPassword($this->customer->getEmail(), $this->request->post['password']);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('account/account', 'token=' . $this->session->data['token'], true));
		}
		
		$this->document->setTitle($data['heading_title']);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $data['text_home'],
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_account'],
			'href' => $this->url->link('account/account', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['heading_title'],
			'href' => $this->url->link('account/password', 'token=' . $this->session->data['token'], true)
		);
		
		if (!empty($this->error)) {
			foreach ($this->error as $key => $value) {
				$data['error_' . $key] = $value;
			}
		}
		unset($this->error);
		
		$fields = array(
			'password',
			'confirm',
		);
		
		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} else {
				$data[$field] = '';
			}
		}

		$data['action'] 		= $this->url->link('account/password', 'token=' . $this->session->data['token'], true);

		$data['back'] 			= $this->url->link('account/account', 'token=' . $this->session->data['token'], true);

		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/password', $data));
	}

	protected function validate() {
		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, "UTF-8")) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, "UTF-8")) > 20)) {
			$this->error['password'] = $this->language->get('text_error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('text_error_confirm');
		}

		return !$this->error;
	}
}