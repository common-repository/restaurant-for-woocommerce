<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wxp-menu-container">
    <div class="wxp-menu-top">
        <div class="wxp-menu-top-bar" id="wxp-menu-top-bar"></div>
        <div class="wxp-menu-in">
            <div class="wxp-menu-cat wxp-col-4" id="wxp-menu-cat">
                <?php
                if(isset($cats) && !empty($cats) && is_array($cats)){
                    echo '<ul class="wxp-menu">';
                    $i=0;
                    foreach($cats as $slug=>$cat){
                        $active = $i==0 ? ' active' : '';
                        echo '<li class="menu-cat wxp-menu-'.$slug.''.$active.'" id="menu-cat-'.$slug.'" data-id="'.$slug.'">'.$cat['name'].'<span class="menu-cat-count">('.$cat['count'].')</span></li>';
                        $i++;
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            <div class="wxp-menu-items wxp-col-8">
                <div class="wxp-menu-items-top">
                    <div class="wxp-cat-filter">
                        <?php
                        if(isset($cats) && !empty($cats) && is_array($cats)){
                            echo '<select name="wxp-cat-select" class="wxp-cat-select">';
                            echo '<option value="">'.__('Select Category','wxp-restaurant').'</option>';
                            foreach($cats as $slug=>$cat){
                                echo '<option value="'.$slug.'">'.$cat['name'].' ('.$cat['count'].')</option>';
                            }
                            echo '</select>';
                        }
                        ?>
                    </div>


                    <?php

                    echo '<div class="check-filter">';
                    echo '<label><input id="wxp-is-veg" type="checkbox" class="wxp-item-filter" name="wxp-item-type" value="veg">'.__('Veg Only','wxp-restaurant').'</label>';
                    echo '<label><input id="wxp-is-nonveg"  type="checkbox" class="wxp-item-filter" name="wxp-item-type" value="veg">'.__('Non Veg Only','wxp-restaurant').'</label>';
                    echo '</div>';

                    echo '<div class="wxp-search-item">';
                    echo '<span class="wxp-icon search-icon"></span>';
                    echo '<input type="text" name="wxp-search-item" placeholder="'.__('Search within menu','wxp-restaurant').'">';
                    echo '<span class="wxp-icon search-clear"></span>';
                    echo '</div>';

                    ?>
                </div>
                <?php
                $search_data = array();
                $j=0;
                if(isset($items) && !empty($items) && is_array($items)){
                    echo '<div class="wxp-menu-item-in">';
                    foreach($items as $slug=>$products){
                        $k=0;
                        $active = $j==0 ? 'active' : '';

                        if(isset($cats[$slug])){
                            echo '<div class="wxp-item-head wxp-sec-'.$slug.' '.$active.'" data-slug="'.$slug.'">'.$cats[$slug]['name'].'</div>';
                        }
                        echo '<div class="wxp-items-sec wxp-sec-'.$slug.'">';
                        if(is_array($products) && !empty($products)){
                            foreach($products as $product){

                                $is_non_veg = $product->get_meta('_is_nonveg');
                                $veg_str = $is_non_veg=='yes' ? 'non-veg' : 'veg';

                                $search_data[$slug][$k] = array(
                                        'id'=>$product->get_id(),
                                        'title'=>$product->get_name(),
                                        'desc'=>$product->get_short_description(),
                                        'veg'=> $is_non_veg=='yes' ? false : true,
                                        'non_veg'=> $is_non_veg=='yes' ? true : false,
                                        'cat'=>$slug,
                                    );

                                echo '<div class="wxp-menu-item wxp-item-'.$product->get_id().'">';

                                if($product->get_image_id()){
                                    echo '<div class="wxp-item-img">'.$product->get_image().'</div>';
                                }

                                echo '<div class="wxp-item-mid">';

                                echo '<div class="wxp-item-name"><span class="wxp-icon wxp-item-type '.$veg_str.'"></span>'.$product->get_name().'</div>';

                                $customization = false;
                                $options = wxp_restaurant()->get_options($product);
                                if(!empty($options) && is_array($options)){
                                    $customization = true;
                                    $res = new WooExperts_Load_Restaurant;
                                    $product_attr = $res->esc_data(array('id'=>$product->get_id(),'title'=>$product->get_title(),'price'=>$product->get_price(),'currency'=>esc_attr(get_woocommerce_currency_symbol()),'variable'=>$product->is_type('variable')));
                                    $options_attr = $res->esc_data($options);
                                }

                                echo '<div class="wxp-item-btn-top">';
                                echo '<div class="wxp-item-btn" data-qty="0" data-id="'.$product->get_id().'" data-options="'.$customization.'">';
                                echo '<div class="wxp-icon wxp-btn-m"></div>';
                                echo '<div class="wxp-btn-qty">'.__('Add','wxp-restaurant').'</div>';
                                echo '<div class="wxp-icon wxp-btn-p"></div>';
                                echo '</div>';

                                if($customization){
                                    echo '<div class="wxp-item-opts" data-product="'.$product_attr.'" data-options="'.$options_attr.'">Customization</div>';
                                }


                                echo '</div>';

                                echo '<div class="wxp-item-price">'.$product->get_price_html().'</div>';
                                echo '<div class="wxp-item-desc">'.$product->get_short_description().'</div>';

                                echo '</div>';


                                echo '<div class="wxp-cls"></div>';
                                echo '</div>';
                                $k++;
                            }
                        }
                        echo '</div>';

                        $j++;

                    }
                    echo '</div>';
                }
                ?>
            </div>
            <div class="wxp-cls"></div>
            <?php
            $search_json = wp_json_encode($search_data);
            $search_attr = function_exists('wc_esc_json') ? wc_esc_json($search_json) : _wp_specialchars($search_json,ENT_QUOTES,'UTF-8',true);
            ?>
            <div class="wxp-menu-data" data-menu="<?php echo $search_attr; ?>"></div>
        </div>
    </div>
</div>
<div class="wxp-icon wxp-side-icon"><span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span></div>
<div id="wxpmenu-cart" class="wxp-res-menu wxpmenu-nav">
    <span class="wxp-icon close-wxp-cart"></span>
    <div class="wxp-cart-in">
        <h4><?php echo __('Your Basket','wxp-restaurant'); ?></h4>
        <?php
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
        ?>
    </div>
</div>
