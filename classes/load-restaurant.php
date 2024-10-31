<?php
defined( 'ABSPATH' ) || exit;

class WooExperts_Load_Restaurant{

    public function __construct(){
        $this->base  = wxp_restaurant()->get_base();
        $this->base_url  = wxp_restaurant()->get_base_url();
        $this->footer  = wxp_restaurant()->script_footer();
        add_action('wp_enqueue_scripts',array($this,'front_scripts'),10);
        add_action('admin_enqueue_scripts',array($this,'admin_scripts'),999);
    }

    public function init(){
        add_filter('product_type_options',array($this,'add_type_checkbox'),10,1);
        add_action('save_post_product',array($this,'save_type_checkbox'),10,3);
        add_shortcode('wxp_restaurant',array($this,'display_menu'));
        add_filter('woocommerce_get_item_data',array($this,'menu_item_data'),10,2);
        add_action('woocommerce_cart_loaded_from_session',array($this,'menu_item_data_session'),999,1);
        add_action('woocommerce_checkout_create_order_line_item',array($this,'menu_item_order_data'),10,4);
    }

    function add_type_checkbox($types){
        $types['is_nonveg'] = array(
            'id'            => '_is_nonveg',
            'wrapper_class' => 'show_if_simple show_if_variable',
            'label'         => __('Is Non-Vegetarian','wxp-restaurant'),
            'description'   => __('Check if item is Non-Vegetarian.','wxp-restaurant'),
            'default'       => 'no',
        );
        return $types;
    }

    function save_type_checkbox($post_id,$product,$update){
        update_post_meta(
            $post_id
            ,'_is_nonveg'
            ,isset($_POST['_is_nonveg']) && $_POST['_is_nonveg']=='on' ? 'yes' : 'no'
        );
    }

    function menu_item_data($item_data,$cart_data){

        if(isset($cart_data['wxp-menu']) && is_array($cart_data['wxp-menu']) && !empty($cart_data['wxp-menu'])){
            foreach($cart_data['wxp-menu'] as $opt_key=>$opts){
                $options = '';
                $options_price = 0;
                if(isset($opts['options']) && is_array($opts['options']) && !empty($opts['options'])){
                    foreach($opts['options'] as $option){
                        $price_str = isset($option['price']) && $option['price']>0 ? ' (+'.get_woocommerce_currency_symbol().$this->get_price($option['price']).')' : '';
                        $options.= $option['name'].$price_str."\r\n";
                        if(isset($option['price']) && $option['price']>0){
                            $options_price = $options_price + ($option['price']*$cart_data['quantity']);
                        }
                    }
                }
                $item_data[] = array(
                    'key' => $opts['title'],
                    'display' => $options,
                    'price' => $options_price,
                );
            }
        }
        if(is_array($item_data) && !empty($item_data)){
            foreach($item_data as $item_key=>$item){
                if(isset($item['key']) && trim($item['key'])=='' && isset($item['value']) && trim($item['value'])==''){
                    unset($item_data[$item_key]);
                }
                elseif(isset($item['key']) && trim($item['key'])=='' && isset($item['display']) && trim($item['display'])==''){
                    unset($item_data[$item_key]);
                }
            }
        }

        return $item_data;
    }

    function menu_item_data_session($cart){
        if(isset($cart->cart_contents) && is_array($cart->cart_contents) && !empty($cart->cart_contents)){
            foreach($cart->cart_contents as $cart_item_key=>$cart_item){
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

    function menu_item_order_data($item,$cart_item_key,$values,$order){
        if(isset($values['wxp-menu']) && is_array($values['wxp-menu']) && !empty($values['wxp-menu'])){
            foreach($values['wxp-menu'] as $option){
                $option_str = '';
                if(isset($option['options']) && is_array($option['options'])){
                    foreach($option['options'] as $opt){
                        $price_str = isset($opt['price']) && $opt['price']>0 ? ' (+'.get_woocommerce_currency_symbol().$this->get_price($opt['price']).')' : '';
                        $option_str.= $opt['name'].$price_str."\r\n";
                    }
                }
                if(isset($option['title'])){
                    $item->add_meta_data($option['title'],$option_str);
                }
            }
        }
    }

    function get_price($price,$args = array()){
        $args = apply_filters(
            'wc_price_args',
            wp_parse_args(
                $args,
                array(
                    'ex_tax_label'       => false,
                    'currency'           => '',
                    'decimal_separator'  => wc_get_price_decimal_separator(),
                    'thousand_separator' => wc_get_price_thousand_separator(),
                    'decimals'           => wc_get_price_decimals(),
                    'price_format'       => get_woocommerce_price_format(),
                )
            )
        );
        $price = number_format($price,$args['decimals'],$args['decimal_separator'],$args['thousand_separator']);
        return $price;
    }

	function display_menu($atts){
        if(!is_admin()){
            wp_enqueue_style('wxp-fancybox-css');
            wp_enqueue_script('wxp-fancybox-js');

            wp_enqueue_style('restaurant-style');
            wp_enqueue_script('jquery-ui-tooltip');
            wp_enqueue_script('restaurant-script');

            $atts = shortcode_atts(array(
                'menu'=>'lunch',
            ),$atts,'wxp_restaurant');
            $menu = $this->get_menu_html($atts);
            return $menu;
        }
    }

    function get_menu_html($atts){
        ob_start();
        $items = $this->get_menu_items($atts);
        $categories = $this->get_menu_categories($atts['menu']);
        wc_get_template('menu.php',array('items'=>$items,'cats'=>$categories),'',$this->base.'templates/');
        return ob_get_clean();
    }

    function get_menu_items($atts){
        $args = array('status'=>'publish');
        $items = array();
        if(isset($atts['menu']) && $atts['menu']!=''){
            $categories = $this->get_menu_categories($atts['menu']);
            if(is_array($categories) && !empty($categories)){
                foreach($categories as $slug=>$cat){
                    $args['category'] = array($slug);
                    $items[$slug] = wc_get_products($args);
                }
            }
        }
        return $items;
    }

    function get_menu_categories($menu){
        $cats = array();
        $terms = get_terms( array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
        ));
        if(is_array($terms) && !empty($terms)){
            foreach($terms as $term){
                $cats[$term->slug]['name'] = $term->name;
                $cats[$term->slug]['count'] = $term->count;
            }
        }
        return $cats;
    }

    function front_scripts(){

        wp_register_script('wxp-fancybox-js',$this->base_url.'/assets/js/jquery.fancybox.min.js',array('jquery'),WXP_RESTAURANT_VER,$this->footer);
        wp_register_style('wxp-fancybox-css',$this->base_url.'/assets/css/jquery.fancybox.css',array(),WXP_RESTAURANT_VER);
        wp_register_style('restaurant-style',$this->base_url.'/assets/css/front.css',array(),WXP_RESTAURANT_VER);

        wp_register_script('restaurant-sticky',$this->base_url.'/assets/js/jquery.sticky.js',array('jquery'),WXP_RESTAURANT_VER,$this->footer);
        wp_register_script('restaurant-script',$this->base_url.'/assets/js/menu.js',array('jquery'),WXP_RESTAURANT_VER,$this->footer);
        $translation = array(
            'wxp_select'       => __('Select Option','wxp-restaurant'),
            'wxp_add_to_cart'  => __('Add to cart','wxp-restaurant'),
            'wxp_added_to_cart'  => __('Item successfully added to your cart.','wxp-restaurant'),
            'wxp_select_opt'  => __('Please select option.','wxp-restaurant'),
            'wxp_clear'       => __('Clear Selection','wxp-restaurant'),
            'wxp_no_item'       => __('No items found that match your search/filter.','wxp-restaurant'),
            'wxp_currency'     => get_woocommerce_currency_symbol(),
            'wxp_ajax'         => trailingslashit(site_url()),
            'wxp_nonce'        => wp_create_nonce('wooexperts-menu'),
        );
        wp_localize_script('restaurant-script','wxpmenu',$translation);
    }

    function admin_scripts(){
        $screen = get_current_screen();
        if(isset($screen->id) && in_array($screen->id,array('woocommerce_page_wxp-restaurant'))){

            wp_enqueue_style('restaurant-color',$this->base_url.'/assets/css/spectrum.css',array(),WXP_RESTAURANT_VER);
            wp_enqueue_script('restaurant-color-js',$this->base_url.'/assets/js/spectrum.js',array('jquery'),WXP_RESTAURANT_VER,$this->footer);

            wp_enqueue_style('restaurant-admin',$this->base_url.'/assets/css/admin.css',array(),WXP_RESTAURANT_VER);
            wp_enqueue_script('restaurant-admin-js',$this->base_url.'/assets/js/admin.js',array('jquery'),WXP_RESTAURANT_VER,$this->footer);
        }
    }

    function esc_data($options){
        $options_data = wp_json_encode($options);
        $options_attr = function_exists('wc_esc_json') ? wc_esc_json($options_data) : _wp_specialchars($options_data,ENT_QUOTES,'UTF-8',true);
        return $options_attr;
    }
}
?>