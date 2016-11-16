<?php
class ControllerAccountOrder extends Controller {
	public function index() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/order'));
		
		if (!$this->customer->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}
		
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/order', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['orders'] = array();

		$this->load->model('account/order');

		$order_total = $this->model_account_order->getTotalOrders();

		$results = $this->model_account_order->getOrders(($page - 1) * 10, 10);
		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id'		=> $result['order_id'],
				'extension'		=> $result['extension'],
				'total'			=> $this->currency->format($result['total'], $this->config->get('config_currency_code')),
				'status'		=> $result['status'],
				'date_added'	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'			=> $this->url->link('account/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true),
			);
		}

		$pagination 			= new Pagination();
		$pagination->total 		= $order_total;
		$pagination->page 		= $page;
		$pagination->limit 		= 10;
		$pagination->url 		= $this->url->link('account/order', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] 	= $pagination->render();

		$data['results'] 		= sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/order_list', $data));
	}

	public function info() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/order'));
		
		if (!$this->customer->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('account/order');
		
		$order_id 		= isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;
		$order_info 	= $this->model_account_order->getOrder($order_id);
		
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
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
			'href' => $this->url->link('account/order', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_order'],
			'href' => $this->url->link('account/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . $url, true)
		);
		
		$data['success']		= isset($this->session->data['success']) ? $this->session->data['success'] : '';
		unset($this->session->data['success']);

		$data['error_warning'] 	= isset($this->session->data['error']) ? $this->session->data['error'] : '';
		unset($this->session->data['error']);
		
		$data['back'] 			= $this->url->link('account/order', 'token=' . $this->session->data['token'] . $url, true);
		
		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/header');

		if ($order_info) {
			$data['invoice_no']		= ($order_info['invoice_no']) ? $order_info['invoice_prefix'] . $order_info['invoice_no'] : '';
			
			$data['order_id'] 		= $order_id;
			$data['date_added'] 	= date($data['date_format_short'], strtotime($order_info['date_added']));

			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['firstname'],
				'lastname'  => $order_info['lastname'],
				'company'   => $order_info['company'],
				'address_1' => $order_info['address_1'],
				'address_2' => $order_info['address_2'],
				'city'      => $order_info['city'],
				'postcode'  => $order_info['postcode'],
				'zone'      => $order_info['zone'],
				'zone_code' => $order_info['zone_code'],
				'country'   => $order_info['country']
			);

			$data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['payment_method'] = $order_info['payment_method'];

			// Totals
			$data['totals'] = array();
			$totals = $this->model_account_order->getOrderTotals($order_id);
			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->config->get('config_currency_code')),
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			// History
			$data['histories'] = array();
			$results = $this->model_account_order->getOrderHistories($order_id);
			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			$data['renew'] 			= $this->url->link('account/order/renew', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . $url, true);
			$data['support'] 		= $this->url->link('account/order/support', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . $url, true);

			$this->response->setOutput($this->load->view('account/order_info', $data));
		} else {
			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function renew() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/order'));
		
		if (!$this->customer->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('account/order');
		
		$order_id 		= isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;
		$order_info 	= $this->model_account_order->getOrder($order_id);
		
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if ($order_info) {
			$this->response->redirect($this->url->link('marketplace/purchase', 'token=' . $this->session->data['token'] . '&extension_id=' . $order_info['extension_id']));
		} else {
			$this->session->data['error'] = $data['text_error_renew'];
			$this->response->redirect($this->url->link('account/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . $url));
		}
	}
	
	public function support() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/order'));
		
		if (!$this->customer->isLogged() || !isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);
			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('account/order');
		
		$order_id 		= isset($this->request->get['order_id']) ? $this->request->get['order_id'] : 0;
		$order_info 	= $this->model_account_order->getOrder($order_id);
		
		$url = '';
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if ($order_info) {
			$this->response->redirect($this->url->link('marketplace/support', 'token=' . $this->session->data['token'] . '&extension_id=' . $order_info['extension_id']));
		} else {
			$this->session->data['error'] = $data['text_error_support'];
			$this->response->redirect($this->url->link('account/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id . $url));
		}
	}
}