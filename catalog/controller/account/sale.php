<?php
class ControllerAccountSale extends Controller {
	public function index() {
		$data = array();
		$data = array_merge($data, $this->load->language('account/sale'));
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/sale', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->setTitle($data['heading_title']);

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
			'text' => $data['text_sale'],
			'href' => $this->url->link('account/sale', '', true)
		);

		$this->load->model('account/sale');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['sales'] = array();

		$filter_data = array(
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);

		$sale_total = $this->model_account_sale->getTotalSales();

		$results = $this->model_account_sale->getSales($filter_data);

		foreach ($results as $result) {
			$data['sales'][] = array(
				'order_id'		=> $result['order_id'],
				'extension'		=> $result['extension'],
				'name'       	=> $result['firstname'] . ' ' . $result['lastname'],
				'status'     	=> $result['status'],
				'total'      	=> $this->currency->format($result['total'], $this->config->get('config_currency')),
				'date_added'  	=> date($data['date_format_short'], strtotime($result['date_added']))
			);
		}

		$pagination 			= new Pagination();
		$pagination->total 		= $sale_total;
		$pagination->page 		= $page;
		$pagination->limit 		= 10;
		$pagination->url 		= $this->url->link('account/sale', 'page={page}', true);

		$data['pagination'] 	= $pagination->render();

		$data['results'] 		= sprintf($data['text_pagination'], ($sale_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($sale_total - 10)) ? $sale_total : ((($page - 1) * 10) + 10), $sale_total, ceil($sale_total / 10));

		$data['continue'] 		= $this->url->link('account/account', '', true);

		$data['column_left'] 	= $this->load->controller('common/column_left');
		$data['column_right'] 	= $this->load->controller('common/column_right');
		$data['content_top'] 	= $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] 		= $this->load->controller('common/footer');
		$data['header'] 		= $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/transaction', $data));
	}
}