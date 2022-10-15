<?php


class  ModelExtensionModuleCoupons extends Model {
	public function addCoupons($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "coupons_demo SET coupon_code = '" . $this->db->escape($data['coupon_code']) . "', coupon_price = '" . $this->db->escape($data['coupon_price']) . "', category_id = '" . (float)$data['category_id'] . "', product_id = '" . $this->db->escape($data['product_id']) . "', order_number = '" . (float)$data['order_number'] . "', cart_total = " . (float)$data['cart_total'] . ", coupon_used = '" . $this->db->escape($data['coupon_used']) . "', status = '" . (int)$data['status'] . "'");
	}

	public function editCoupondata($coupon_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "coupons_demo SET coupon_code = '" . $this->db->escape($data['coupon_code']) . "', coupon_price = '" . $this->db->escape($data['coupon_price']) . "', category_id = '" . (float)$data['category_id'] . "', product_id = '" . $this->db->escape($data['product_id']) . "', order_number = '" . (float)$data['order_number'] . "', cart_total = " . (float)$data['cart_total'] . ", coupon_used = '" . $this->db->escape($data['coupon_used']) . "', status = '" . (int)$data['status'] . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_id . "'");

		if (isset($data['coupon_product'])) {
			foreach ($data['coupon_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_product SET coupon_id = '" . (int)$coupon_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_category WHERE coupon_id = '" . (int)$coupon_id . "'");

		if (isset($data['coupon_category'])) {
			foreach ($data['coupon_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_category SET coupon_id = '" . (int)$coupon_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
	}

	public function deleteCoupon($coupon_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupons_demo WHERE coupon_id = '" . (int)$coupon_id . "'");
	}

	public function getCoupon($coupon_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "coupons_demo WHERE coupon_id = '" . (int)$coupon_id . "'");

		return $query->row;
	}



	public function AllCoupons() {
		$sql = "SELECT * FROM " . DB_PREFIX . "coupons_demo";


		$query = $this->db->query($sql);

		return $query->rows;
	}


}