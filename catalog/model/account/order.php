<?php
class ModelAccountOrder extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND order_status_id > '0'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$iso_code_2 = '';
				$iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['zone_id'] . "'");
			$zone_code	= ($zone_query->num_rows) ? $zone_query->row['code'] : '';

			return array(
				'order_id'				=> $order_query->row['order_id'],
				'customer_id'			=> $order_query->row['customer_id'],
				'extension_id'			=> $order_query->row['extension_id'],
				'extension'				=> $order_query->row['extension'],
				'price'					=> $order_query->row['price'],
				'quantity'				=> $order_query->row['quantity'],
				'total'					=> $order_query->row['total'],
				'invoice_no'			=> $order_query->row['invoice_no'],
				'invoice_prefix'		=> $order_query->row['invoice_prefix'],
				'firstname'				=> $order_query->row['firstname'],
				'lastname'				=> $order_query->row['lastname'],
				'email'					=> $order_query->row['email'],
				'telephone'				=> $order_query->row['telephone'],
				'company'				=> $order_query->row['company'],
				'address_1'				=> $order_query->row['address_1'],
				'address_2'				=> $order_query->row['address_2'],
				'city'					=> $order_query->row['city'],
				'postcode'				=> $order_query->row['postcode'],
				'country'				=> $order_query->row['country'],
				'country_id'			=> $order_query->row['country_id'],
				'iso_code_2'			=> $iso_code_2,
				'iso_code_3'			=> $iso_code_3,
				'zone'					=> $order_query->row['zone'],
				'zone_id'				=> $order_query->row['zone_id'],
				'zone_code'				=> $zone_code,
				'payment_method'		=> $order_query->row['payment_method'],
				'comment'				=> $order_query->row['comment'],
				'order_status_id'		=> $order_query->row['order_status_id'],
				'status'				=> $order_query->row['status'],
				'date_modified'			=> $order_query->row['date_modified'],
				'date_added'			=> $order_query->row['date_added'],
				'ip'					=> $order_query->row['ip']
			);
		} else {
			return false;
		}
	}

	public function getOrders($start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 1;
		}

		$query = $this->db->query("SELECT o.order_id, o.extension, os.name as status, o.date_added, o.total FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0' ORDER BY o.order_id DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getOrderTotals($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "' ORDER BY sort_order");

		return $query->rows;
	}

	public function getOrderHistories($order_id) {
		$query = $this->db->query("SELECT date_added, os.name AS status, oh.comment, oh.notify FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int)$order_id . "' ORDER BY oh.date_added");

		return $query->rows;
	}

	public function getTotalOrders() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o WHERE customer_id = '" . (int)$this->customer->getId() . "' AND o.order_status_id > '0'");

		return $query->row['total'];
	}

	public function getTotalOrderProductsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
}