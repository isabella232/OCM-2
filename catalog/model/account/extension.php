<?php
class ModelAccountExtension extends Model {
	private $db_table	= 'product';
	
	public function addExtension($data) {
		$data['images'] = !empty($data['images']) ? $data['images'] : array();
		
		$this->db->query("INSERT INTO " . DB_PREFIX . $this->db_table . " SET seller_id = '" . (int)$this->customer->getId() . "', name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', documentation = '" . $this->db->escape($data['documentation']) . "', changelog = '" . $this->db->escape($data['changelog']) . "', category_id = '" . (int)$data['category_id'] . "', license = '" . (int)$data['license'] . "', license_period = '" . (int)$data['license_period'] . "', price = '" . (float)$data['price'] . "', price_renew = '" . (float)$data['price_renew'] . "', demo_catalog = '" . $this->db->escape($data['demo_catalog']) . "', demo_admin = '" . $this->db->escape($data['demo_admin']) . "', demo_user = '" . $this->db->escape($data['demo_user']) . "', demo_pass = '" . $this->db->escape($data['demo_pass']) . "', tag = '" . $this->db->escape($data['tag']) . "', image = '" . $this->db->escape($data['image']) . "', banner = '" . $this->db->escape($data['banner']) . "', images = '" . $this->db->escape(json_encode($data['images'])) . "', downloads = '" . $this->db->escape(json_encode($data['downloads'])) . "', ga_tracking = '" . $this->db->escape($data['ga_tracking']) . "', status = '" . (int)$data['status'] . "', approved = '0', date_added = NOW(), date_modified = NOW()");
	
		return $this->db->getLastId();
	}
	
	public function editExtension($extension_id, $data) {
		$data['images'] = !empty($data['images']) ? $data['images'] : array();
		
		$this->db->query("UPDATE " . DB_PREFIX . $this->db_table . " SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', documentation = '" . $this->db->escape($data['documentation']) . "', changelog = '" . $this->db->escape($data['changelog']) . "', category_id = '" . (int)$data['category_id'] . "', license = '" . (int)$data['license'] . "', license_period = '" . (int)$data['license_period'] . "', price = '" . (float)$data['price'] . "', price_renew = '" . (float)$data['price_renew'] . "', demo_catalog = '" . $this->db->escape($data['demo_catalog']) . "', demo_admin = '" . $this->db->escape($data['demo_admin']) . "', demo_user = '" . $this->db->escape($data['demo_user']) . "', demo_pass = '" . $this->db->escape($data['demo_pass']) . "', tag = '" . $this->db->escape($data['tag']) . "', image = '" . $this->db->escape($data['image']) . "', banner = '" . $this->db->escape($data['banner']) . "', images = '" . $this->db->escape(json_encode($data['images'])) . "', downloads = '" . $this->db->escape(json_encode($data['downloads'])) . "', ga_tracking = '" . $this->db->escape($data['ga_tracking']) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE extension_id = '" . (int)$extension_id . "' AND seller_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getExtension($extension_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $this->db_table . " WHERE extension_id = '" . (int)$extension_id . "' AND seller_id = '" . (int)$this->customer->getId() . "'");
		if ($query->num_rows) {
			return array(
				'extension_id'		=> $query->row['extension_id'],
				'seller_id'			=> $query->row['seller_id'],
				'name'				=> $query->row['name'],
				'description'		=> $query->row['description'],
				'documentation'		=> $query->row['documentation'],
				'changelog'			=> $query->row['changelog'],
				'category_id'		=> $query->row['category_id'],
				'license'			=> $query->row['license'],
				'license_period'	=> $query->row['license_period'],
				'price'				=> $query->row['price'],
				'price_renew'		=> $query->row['price_renew'],
				'demo_catalog'		=> $query->row['demo_catalog'],
				'demo_admin'		=> $query->row['demo_admin'],
				'demo_user'			=> $query->row['demo_user'],
				'demo_pass'			=> $query->row['demo_pass'],
				'tag'				=> $query->row['tag'],
				'image'				=> $query->row['image'],
				'banner'			=> $query->row['banner'],
				'images'			=> json_decode($query->row['images'], true),
				'downloads'			=> json_decode($query->row['downloads'], true),
				'ga_tracking'		=> $query->row['ga_tracking'],
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
		$extension_data = array();
		
		$sql = "SELECT extension_id FROM " . DB_PREFIX . $this->db_table . " WHERE seller_id = '" . (int)$this->customer->getId() . "'";
		
		$sql .= " ORDER BY ";
		$sql .= (isset($data['sort'])) ? $this->db->escape($data['sort']) : "name";
		
		$sql .= " ";
		$sql .= (isset($data['order'])) ? $this->db->escape($data['order']) : "ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
		
		foreach ($query->rows as $result) {
			$extension_data[$result['extension_id']] = $this->getExtension($result['extension_id']);
		}

		return $extension_data;
	}

	public function getTotalExtensions($data = array()) {
		$query = $this->db->query("SELECT COUNT(DISTINCT extension_id) AS total FROM " . DB_PREFIX . $this->db_table . " WHERE seller_id = '" . (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
}
