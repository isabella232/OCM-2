<?php
class ModelMarketplaceExtension extends Model {
	public function getExtension($extension_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product WHERE extension_id = '" . (int)$extension_id . "' AND status = '1' AND approved = '1'");

		if ($query->num_rows) {
			return array(
				'extension_id'		=> $query->row['extension_id'],
				'customer_id'		=> $query->row['customer_id'],
				'category_id'		=> $query->row['category_id'],
				'name'				=> $query->row['name'],
				'description'		=> $query->row['description'],
				'documentation'		=> $query->row['documentation'],
				'changelog'			=> $query->row['changelog'],
				'license'			=> $query->row['license'],
				'license_period'	=> $query->row['license_period'],
				'price'				=> $query->row['price'],
				'price_renew'		=> $query->row['price_renew'],
				'thumb'				=> $query->row['thumb'],
				'banner'			=> $query->row['banner'],
				'image'				=> json_decode($query->row['price_renew'], true),
				'download'			=> json_decode($query->row['price_renew'], true),
				'ga_tracking'		=> $query->row['ga_tracking'],
				'tag'				=> $query->row['tag'],
				'status'			=> $query->row['status'],
				'approved'			=> $query->row['approved'],
				'date_added'		=> $query->row['date_added'],
				'date_modified'		=> $query->row['date_modified'],
			);
		} else {
			return false;
		}
	}

	public function getExtensions($data = array()) {
		$sql = "SELECT extension_id FROM " . DB_PREFIX . "product WHERE status = '1' AND approved = '1' ORDER BY name ASC";

		// if (!empty($data['filter_category_id'])) {
			// if (!empty($data['filter_sub_category'])) {
				// $sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			// } else {
				// $sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			// }

			// if (!empty($data['filter_filter'])) {
				// $sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			// } else {
				// $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			// }
		// } else {
			// $sql .= " FROM " . DB_PREFIX . "product p";
		// }

		// $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		// if (!empty($data['filter_category_id'])) {
			// if (!empty($data['filter_sub_category'])) {
				// $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			// } else {
				// $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			// }

			// if (!empty($data['filter_filter'])) {
				// $implode = array();

				// $filters = explode(',', $data['filter_filter']);

				// foreach ($filters as $filter_id) {
					// $implode[] = (int)$filter_id;
				// }

				// $sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			// }
		// }

		// if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			// $sql .= " AND (";

			// if (!empty($data['filter_name'])) {
				// $implode = array();

				// $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				// foreach ($words as $word) {
					// $implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				// }

				// if ($implode) {
					// $sql .= " " . implode(" AND ", $implode) . "";
				// }

				// if (!empty($data['filter_description'])) {
					// $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				// }
			// }

			// if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				// $sql .= " OR ";
			// }

			// if (!empty($data['filter_tag'])) {
				// $implode = array();

				// $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				// foreach ($words as $word) {
					// $implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				// }

				// if ($implode) {
					// $sql .= " " . implode(" AND ", $implode) . "";
				// }
			// }

			// if (!empty($data['filter_name'])) {
				// $sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			// }

			// $sql .= ")";
		// }

		// if (!empty($data['filter_manufacturer_id'])) {
			// $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		// }

		// $sql .= " GROUP BY p.product_id";

		// $sort_data = array(
			// 'pd.name',
			// 'p.model',
			// 'p.quantity',
			// 'p.price',
			// 'rating',
			// 'p.sort_order',
			// 'p.date_added'
		// );

		// if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			// if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				// $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			// } elseif ($data['sort'] == 'p.price') {
				// $sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			// } else {
				// $sql .= " ORDER BY " . $data['sort'];
			// }
		// } else {
			// $sql .= " ORDER BY p.sort_order";
		// }

		// if (isset($data['order']) && ($data['order'] == 'DESC')) {
			// $sql .= " DESC, LCASE(pd.name) DESC";
		// } else {
			// $sql .= " ASC, LCASE(pd.name) ASC";
		// }

		// if (isset($data['start']) || isset($data['limit'])) {
			// if ($data['start'] < 0) {
				// $data['start'] = 0;
			// }

			// if ($data['limit'] < 1) {
				// $data['limit'] = 20;
			// }

			// $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		// }

		$extension_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$extension_data[$result['extension_id']] = $this->getExtension($result['extension_id']);
		}

		return $product_data;
	}

	public function getTotalExtensions($data = array()) {
		// $sql = "SELECT COUNT(DISTINCT p.extension_id) AS total";

		// if (!empty($data['filter_category_id'])) {
			// if (!empty($data['filter_sub_category'])) {
				// $sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			// } else {
				// $sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			// }

			// if (!empty($data['filter_filter'])) {
				// $sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			// } else {
				// $sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			// }
		// } else {
			// $sql .= " FROM " . DB_PREFIX . "product p";
		// }

		// $sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		// if (!empty($data['filter_category_id'])) {
			// if (!empty($data['filter_sub_category'])) {
				// $sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			// } else {
				// $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			// }

			// if (!empty($data['filter_filter'])) {
				// $implode = array();

				// $filters = explode(',', $data['filter_filter']);

				// foreach ($filters as $filter_id) {
					// $implode[] = (int)$filter_id;
				// }

				// $sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			// }
		// }

		// if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			// $sql .= " AND (";

			// if (!empty($data['filter_name'])) {
				// $implode = array();

				// $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				// foreach ($words as $word) {
					// $implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				// }

				// if ($implode) {
					// $sql .= " " . implode(" AND ", $implode) . "";
				// }

				// if (!empty($data['filter_description'])) {
					// $sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				// }
			// }

			// if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				// $sql .= " OR ";
			// }

			// if (!empty($data['filter_tag'])) {
				// $implode = array();

				// $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				// foreach ($words as $word) {
					// $implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				// }

				// if ($implode) {
					// $sql .= " " . implode(" AND ", $implode) . "";
				// }
			// }

			// if (!empty($data['filter_name'])) {
				// $sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				// $sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			// }

			// $sql .= ")";
		// }

		// if (!empty($data['filter_manufacturer_id'])) {
			// $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		// }
		
		$sql = "SELECT COUNT(DISTINCT extension_id) AS total FROM " . DB_PREFIX . "product WHERE status = '1' AND approved = '1'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
