<?php
defined( 'ABSPATH' ) || exit;
if(!class_exists('RFW_WExperts_AJAX')){
    class RFW_WExperts_AJAX{

        public static function init(){
            add_action('init',array(__CLASS__,'define_ajax'), 0);
            add_action('template_redirect', array(__CLASS__,'do_rfw_wexperts_ajax'), 0);
            self::add_ajax_events();
        }

        public static function define_ajax(){

            if(!empty($_GET['wexperts-ajax'])){
                //wp_plugin_directory_constants();
                wc_maybe_define_constant('DOING_AJAX', true);
                wc_maybe_define_constant('WEXPERTS_DOING_AJAX', true);
                if (!WP_DEBUG || (WP_DEBUG && !WP_DEBUG_DISPLAY)) {
                    @ini_set('display_errors', 0);
                }
                $GLOBALS['wpdb']->hide_errors();
            }
        }

        private static function rfw_wexperts_ajax_headers(){
            if (!headers_sent()) {
                send_origin_headers();
                send_nosniff_header();
                wc_nocache_headers();
                header('Content-Type: text/html; charset=' . get_option('blog_charset'));
                header('X-Robots-Tag: noindex');
                status_header(200);
            } elseif (defined('WP_DEBUG') && WP_DEBUG) {
                headers_sent($file, $line);
                trigger_error("rfw_wexperts_ajax_headers cannot set headers - headers already sent by {$file} on line {$line}", E_USER_NOTICE); // @codingStandardsIgnoreLine
            }
        }

        public static function do_rfw_wexperts_ajax(){
            global $wp_query;

            if(!empty($_GET['wexperts-ajax'])){
                $wp_query->set('wexperts-ajax', sanitize_text_field(wp_unslash($_GET['wexperts-ajax'])));
            }

            $action = $wp_query->get('wexperts-ajax');

            if($action){
                self::rfw_wexperts_ajax_headers();
                $action = sanitize_text_field($action);
                do_action('rfw_wexperts_ajax_'.$action);
                wp_die();
            }
            // phpcs:enable
        }

        public static function add_ajax_events(){
            $ajax_events_nopriv = array(
                'add_to_cart',
                'remove_item'
            );

            foreach ($ajax_events_nopriv as $ajax_event) {
                add_action('rfw_wexperts_ajax_'.$ajax_event,array(__CLASS__,$ajax_event));
            }
        }

        public static function searchbydata($id,$array){
            foreach($array as $key => $val){
                if($val['data_name'] == $id){
                    return $key;
                }
            }
            return null;
        }

        public static function get_option($val,$opts){

            $item_opt = array('title'=>isset($opts['title']) ?  $opts['title'] : '','options'=>array());
            $opt = current($val);
            if(is_array($opt) && !empty($opt)){
                $k=0;
                foreach($opt as $item_option){
                    if(isset($opts['options']) && is_array($opts['options']) && !empty($opts['options'])){
                        foreach($opts['options'] as $option){
                            if($item_option==$option['id']){
                                $item_opt['options'][$k] = array('name'=>$option['option'],'id'=>$option['id'],'price'=>$option['price']);
                                $k++;
                            }
                        }
                    }
                }
            }
            else
            {
                if(isset($opts['options']) && is_array($opts['options']) && !empty($opts['options'])){
                    foreach($opts['options'] as $option){
                        if($opt==$option['id']){
                            $item_opt['options'][] = array('name'=>$option['option'],'id'=>$option['id'],'price'=>$option['price']);
                            break;
                        }
                    }
                }
                else
                {
                    $item_opt['options'][] = array('name'=>$opt,'id'=>$opt,'price'=>$opts['price']);
                }
            }
            return $item_opt;
        }

        public static function recalc(){
            $cart = WC()->cart->get_cart();
            if(is_array($cart) && !empty($cart)){
                foreach($cart as $cart_item_key=>$cart_item){
                    $qty = isset($cart_item['quantity']) ? $cart_item['quantity'] : 1;
                    if(isset($cart_item['wxp-menu']) && is_array($cart_item['wxp-menu']) && !empty($cart_item['wxp-menu'])){
                        $option_price = 0;
                        foreach($cart_item['wxp-menu'] as $option){
                            if(isset($option['options']) && is_array($option['options'])){
                                foreach($option['options'] as $opt){
                                    $price = isset($opt['price']) ? $opt['price'] : 0;
                                    if($price>0){
                                        $option_price = $option_price+$price;
                                    }
                                }
                            }
                        }
                        $item_price = $cart_item['data']->get_price();
                        $cart_item['data']->set_price($item_price+$option_price);
                    }
                }
            }
        }

        public static function get_sidecart_html(){
            ob_start();
            echo '<div class="wxp-cart-in">';
            echo '<h4>'.__('Your Basket','wxp-restaurant').'</h4>';
            $cart = WC()->cart->get_cart();
            if(is_array($cart) && !empty($cart)){
                foreach($cart as $cart_item_key=>$cart_item){
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                    echo '<div class="wxpm-row">';
                    echo '<div class="wxp-s-col-1 wxpc-rm"><span data-id="'.$cart_item_key.'" class="wxp-icon remove-item"></span></div>';
                    echo '<div class="wxp-s-col-2 wxpc-thumbnail">'.apply_filters('woocommerce_cart_item_thumbnail',$_product->get_image(),$cart_item,$cart_item_key).'</div>';
                    echo '<div class="wxp-s-col-5 wxpc-title">';
                    echo apply_filters('woocommerce_cart_item_name',$_product->get_name(),$cart_item,$cart_item_key);
                    echo  '<span class="cart-opts">'.wc_get_formatted_cart_item_data($cart_item).'</span>';
                    echo '</div>';
                    echo '<div class="wxp-s-col-1 wxpc-qty">'.$cart_item['quantity'].'</div>';
                    echo '<div class="wxp-s-col-3 wxpc-total">'.apply_filters('woocommerce_cart_item_price',WC()->cart->get_product_price($_product),$cart_item,$cart_item_key).'</div>';
                    echo '</div>';
                }

                echo '<div class="wxpm-row">';
                echo '<div class="wxp-s-col-12 wxpc-checkout">';
                echo '<a href="'.wc_get_cart_url().'">'.__('Cart','wxp-restaurant').'</a>';
                echo '<a href="'.wc_get_checkout_url().'">'.__('Checkout','wxp-restaurant').'<span>'.WC()->cart->get_cart_total().'</span></a>';
                echo '</div>';
                echo '</div>';
            }
            else
            {
                echo '<div class="wxpm-row">';
                echo '<div class="wxp-s-col-12 wxp-empty-cart">'.__('Your basket looks a little empty.','wxp-restaurant').'</div>';
                echo '</div>';
            }
            echo '</div>';
            return ob_get_clean();
        }

        public static function get_cart_count(){
            ob_start();
            echo '<span class="cart-count">';
            echo WC()->cart->get_cart_contents_count();
            echo '</span>';
            return ob_get_clean();
        }

        public static function get_cart_item_key($id){
            $key = false;
            $cart = WC()->cart->get_cart();
            if(is_array($cart) && !empty($cart)){
                foreach($cart as $cart_item_key => $cart_item){
                    if($cart_item['product_id'] == $id){
                        $key = $cart_item_key;
                        break;
                    }
                }
            }
            return $key;
        }

        public static function add_to_cart(){
            $res = false;
            $opts = array();
            parse_str($_POST['data'],$data);

            $var_atts = array();
            $product_id = isset($data['product']) ? $data['product'] : 0;
            $variation_id = isset($data['variation']) ? $data['variation'] : 0;
            if($variation_id){
                $var_atts = wc_get_product_variation_attributes($variation_id);
                $data['opts'] = array();
                $data['opt'] = array();
            }
            if(isset($data['quantity'])){
                if(isset($_POST['check']) && check_ajax_referer('wooexperts-menu','check')){
                    $qty = isset($data['quantity']) ? $data['quantity'] : 1;
                    if($product_id){
                        if(isset($data['opts']) && is_array($data['opts']) && !empty($data['opts'])){
                            $ppom = new PPOM_Meta($product_id);
                            $k=0;
                            if(is_array($data['opts']) && !empty($data['opts'])){
                                foreach($data['opts'] as $opt){
                                    $key = key($opt);
                                    $arr_key = self::searchbydata($key,$ppom->fields);
                                    $opt_data = isset($ppom->fields[$arr_key]) ? $ppom->fields[$arr_key] : array();
                                    if(isset($opt_data['type']) && in_array($opt_data['type'],array('textarea','text'))){
                                        if(isset($opt[$opt_data['data_name']]) && trim($opt[$opt_data['data_name']])==''){
                                            continue;
                                        }
                                    }
                                    $opts[$k] = self::get_option($opt,$opt_data);
                                    $k++;
                                }
                            }
                            WC()->cart->add_to_cart($product_id,$qty,$variation_id,$var_atts,array('wxp-menu'=>$opts));
                        }
                        elseif(isset($data['opt']))
                        {
                            //$key = self::get_cart_item_key($product_id);
                            WC()->cart->add_to_cart($product_id,$qty,$variation_id,$var_atts);
                        }
                        $res = true;
                        self::recalc();
                        WC()->cart->calculate_totals();
                    }
                }
            }
            wp_send_json(array('res'=>$res,'fragments'=>array('div.wxp-cart-in'=>self::get_sidecart_html(),'span.cart-count'=>self::get_cart_count())));
        }

        public static function remove_item(){
            if(isset($_POST['check']) && check_ajax_referer('wooexperts-menu','check')){
                if(isset($_POST['id'])){
                    WC()->cart->remove_cart_item($_POST['id']);
                    WC()->cart->calculate_totals();
                }
            }
            wp_send_json(array('res'=>true,'fragments'=>array('div.wxp-cart-in'=>self::get_sidecart_html(),'span.cart-count'=>self::get_cart_count())));
        }

    }
}

RFW_WExperts_AJAX::init();
