<?php
/*
Plugin Name: Restaurant for WooCommerce
*Plugin URI: https://wpexpertshub.com
Description: Make your restaurant menu online.
Author: WpExperts Hub
Version: 1.1
*Author URI: https://wpexpertshub.com
*Text Domain: wxp-restaurant
*License: GPLv3
*WC requires at least: 5.4
*WC tested up to: 6.7
*Requires at least: 5.4
*Tested up to: 6.0
*Requires PHP: 7.2
*/

defined( 'ABSPATH' ) || exit;

class WooExperts_Restaurant{

    protected static $_instance = null;

    public static function instance(){

        if(is_null(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct(){
        if(!defined('WXP_RESTAURANT_VER')){
            define('WXP_RESTAURANT_VER','1.1');
        }
        add_action('admin_menu',array($this,'admin_menu'));  
        add_action('init',array($this,'init_autoload'));
        add_action('init',array($this,'load_restaurant'));
        add_action('wxp_save_restaurant_settings',array($this,'save_restaurant_settings'),1);
        $this->includes();
    }

    function admin_menu(){
        add_submenu_page('woocommerce',__('Restaurant','wxp-restaurant'),__('Restaurant','wxp-restaurant'),'manage_woocommerce','wxp-restaurant',array($this,'restaurant_dashboard'));
    }

    function init_autoload(){
        spl_autoload_register(function($class){
            $class = strtolower($class);
            $class = str_replace('_','-',$class);
            $class = str_replace('wooexperts-','',$class);
            if(is_file(dirname(__FILE__).'/classes/'.$class.'.php')) {
                include_once('classes/'.$class.'.php');
            }
        });
    }

    function load_restaurant(){
        $restaurant = new WooExperts_Load_Restaurant;
        $restaurant->init();
    }

    function includes(){
        include(dirname(__FILE__).'/classes/ajax.php');
    }

    function restaurant_dashboard(){
        include(dirname(__FILE__).'/templates/dashboard.php');
    }

    function get_base(){
        return trailingslashit(plugin_dir_path(__FILE__));
    }

    function get_base_url(){
        return untrailingslashit(plugins_url('/',__FILE__ ));
    }

    function script_footer(){
        return true;
    }

    function get_options($product){
        $product_id = $product->get_id();
        $options = array();
        if($product->is_type('variable')){
            $product = new WC_Product_Variable($product_id);
            $variations = $product->get_available_variations();
            if(is_array($variations) && !empty($variations)){
                foreach($variations as $variation){
                    if(isset($variation['attributes']) && is_array($variation['attributes'])){
                        $_product = new WC_Product_Variation($variation['variation_id']);
                        $options['options'][] = array(
                            'attributes'=>$variation['attributes'],
                            'variation_id'=>$variation['variation_id'],
                            'price'=>$_product->get_price(),
                            'sku'=>$variation['sku'],
                        );
                    }
                }
                $options['data'] = $product->get_variation_attributes();
            }
        }
        return $options;
    }

}

function wxp_restaurant(){
    return WooExperts_Restaurant::instance();
}

if(function_exists('is_multisite') && is_multisite()){
	if(!function_exists( 'is_plugin_active_for_network')){
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}
	if(is_plugin_active_for_network('woocommerce/woocommerce.php')){
		if(!is_plugin_active_for_network('restaurant-for-woocommerce-pro/restaurant-for-woocommerce-pro.php')){
			wxp_restaurant();
		}
	}
	elseif (in_array('woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins')))) {
		if(!in_array('restaurant-for-woocommerce-pro/restaurant-for-woocommerce-pro.php',apply_filters('active_plugins',get_option('active_plugins')))){
			wxp_restaurant();
		}
	}
}
elseif(in_array('woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins')))){
	if(!in_array('restaurant-for-woocommerce-pro/restaurant-for-woocommerce-pro.php',apply_filters('active_plugins',get_option('active_plugins')))){
		wxp_restaurant();
	}
}