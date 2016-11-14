<?php
class ModelAccountSale extends Model {
	public function getSales() {
		$sql = "SELECT * FROM `" . DB_PREFIX . "order` WHERE seller_id = '" . (int)$this->customer->getId() . "'";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalSales() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE seller_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}

	public function getTotalAmount() {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE seller_id = '" . (int)$this->customer->getId() . "'");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
	
	public function getTotalSalesByExtension($extension_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE extension_id = '" . (int)$extension_id . "'");

		return $query->row['total'];

		return $query->rows;
	}
	
	public function getTotalAmountByExtension($extension_id) {
		$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE extension_id = '" . (int)$extension_id . "'");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}