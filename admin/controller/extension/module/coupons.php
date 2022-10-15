<?php 
class ControllerExtensionModuleCoupons extends Controller {
    public function index() {
        $this->load->language('extension/module/coupons');
		$this->load->model('extension/module/coupons');


        $url = "";
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_coupons'),
            'href' => $this->url->link('extension/module/coupons', '', true)
        );

        
		$results = $this->model_extension_module_coupons->AllCoupons();
   
		foreach ($results as $result) {
			$data['coupons'][] = array(
				'coupon_id'  => $result['coupon_id'],
				'name'       => $result['coupon_code'],
				'order_number'   => $result['order_number'],
				'coupon_price'   => $result['coupon_price'],
				'product_id'   => $result['product_id'],
				'category_id'   => $result['category_id'],
				'cart_total'   => $result['cart_total'],
				
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'edit'       => $this->url->link('extension/module/coupons/edit', 'user_token=' . $this->session->data['user_token'] . '&coupon_id=' . $result['coupon_id'] . $url, true)
			);
		}


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $data['add'] = $this->url->link('extension/module/coupons/add', 'user_token=' . $this->session->data['user_token'] . "", true);
		$data['delete'] = $this->url->link('extension/module/coupons/delete', 'user_token=' . $this->session->data['user_token'] . "", true);

        $this->response->setOutput($this->load->view('extension/module/coupons', $data));
    }
    public function Add(){

        $data = [];
        $url = '';

        $this->load->language('extension/module/coupons');
        
        $data['action'] = $this->url->link('extension/module/coupons/Added', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
   
        $this->response->setOutput($this->load->view('extension/module/coupons_form', $data));

    }
    public function Added(){
        $this->load->language('extension/module/coupons');

		$this->document->setTitle($this->language->get('heading_title'));
        $data = [];
		$this->load->model('extension/module/coupons');
        if ($this->request->server['REQUEST_METHOD'] == 'POST'){
    
                $data["coupon_code"]= $_POST["coupon_code"];
                $data["coupon_price"]= $_POST["coupon_price"];
                $data["category_id"]= $_POST["category_id"];
                $data["product_id"]= $_POST["product_id"];
                $data["order_number"]= $_POST["order_number"];
                $data["cart_total"]= $_POST["cart_total"];
                $data["coupon_limit"]= $_POST["coupon_limit"];
                $data["coupon_used"]= $_POST["coupon_used"];
                $data["status"]= $_POST["status"];

              
			$this->model_extension_module_coupons->addCoupons($data);
            
			$this->response->redirect($this->url->link('extension/module/coupons', 'user_token=' . $this->session->data['user_token'] . "", true));
            
        }
    }
    public function delete() {
		$this->load->language('extension/module/coupons');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/coupons');

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $coupon_id) {
				$this->model_extension_module_coupons->deleteCoupon($coupon_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/coupons', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

	}
    public function edit() {
		$this->load->language('extension/module/coupons');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/coupons');

        $data= [];

        $coupon_info = $this->model_extension_module_coupons->getCoupon($this->request->get['coupon_id']);



        if (isset($this->request->post['coupon_code'])) {
			$data['coupon_code'] = $this->request->post['coupon_code'];
		} elseif (!empty($coupon_info)) {
			$data['coupon_code'] = $coupon_info['coupon_code'];
		} else {
			$data['coupon_code'] = '';
		}

        if (isset($this->request->post['category_id'])) {
			$data['category_id'] = $this->request->post['category_id'];
		} elseif (!empty($coupon_info)) {
			$data['category_id'] = $coupon_info['category_id'];
		} else {
			$data['category_id'] = '';
		}

        if (isset($this->request->post['product_id'])) {
			$data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($coupon_info)) {
			$data['product_id'] = $coupon_info['product_id'];
		} else {
			$data['product_id'] = '';
		}

        if (isset($this->request->post['cart_total'])) {
			$data['cart_total'] = $this->request->post['cart_total'];
		} elseif (!empty($coupon_info)) {
			$data['cart_total'] = $coupon_info['cart_total'];
		} else {
			$data['cart_total'] = '';
		}

        if (isset($this->request->post['order_number'])) {
			$data['order_number'] = $this->request->post['order_number'];
		} elseif (!empty($coupon_info)) {
			$data['order_number'] = $coupon_info['order_number'];
		} else {
			$data['order_number'] = '';
		}

        if (isset($this->request->post['coupon_price'])) {
			$data['coupon_price'] = $this->request->post['coupon_price'];
		} elseif (!empty($coupon_info)) {
			$data['coupon_price'] = $coupon_info['coupon_price'];
		} else {
			$data['coupon_price'] = '';
		}

        if (isset($this->request->post['coupon_limit'])) {
			$data['coupon_limit'] = $this->request->post['coupon_limit'];
		} elseif (!empty($coupon_info)) {
			$data['coupon_limit'] = $coupon_info['coupon_limit'];
		} else {
			$data['coupon_limit'] = '';
		}
        if (isset($this->request->post['coupon_used'])) {
			$data['coupon_used'] = $this->request->post['coupon_used'];
		} elseif (!empty($coupon_info)) {
			$data['coupon_used'] = $coupon_info['coupon_used'];
		} else {
			$data['coupon_used'] = '';
		}
        if (isset($this->request->post['coupon_limit'])) {
			$data['coupon_limit'] = $this->request->post['coupon_limit'];
		} elseif (!empty($coupon_info)) {
			$data['coupon_limit'] = $coupon_info['coupon_limit'];
		} else {
			$data['coupon_limit'] = '';
		}
        if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($coupon_info)) {
			$data['status'] = $coupon_info['status'];
		} else {
			$data['status'] = true;
		}
        $data['cancel'] = $this->url->link('extension/module/coupons', 'user_token=' . $this->session->data['user_token'] . "", true);

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->model_extension_module_coupons->editCoupondata($this->request->get['coupon_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');



			$this->response->redirect($this->url->link('extension/module/coupons', 'user_token=' . $this->session->data['user_token'] . "", true));
		}
     


        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/module/coupons_form', $data));
	}
}