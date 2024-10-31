<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="wrap wpx-dashboard-wrap">
    <h1><?php echo __('Restaurant Settings','wxp-restaurant'); ?>
        <span class="description">V<?php echo WXP_RESTAURANT_VER; ?> lite</span>
        <span class="description pro-only">These options available in Pro Version only.</span>
        <span class="description"><a target="_blank" class="pro-link" href="https://wooexperts.com/plugins/woocommerce-restaurant/">Get Pro Version</a></span>
    </h1>
        <div class="wpx-dashboard-main">
        <div class="wxp-s-row wpx-dashboard-row">
            <div class="wxp-s-col-3">
                <div class="wpx-dashboard-side">
                    <ul class="wxp-admin-nav">
                        <li class="active" id="wxp-menu-1"><?php echo __('Menu Settings','wxp-restaurant'); ?></li>
                        <li id="wxp-menu-2"><?php echo __('Menu Options','wxp-restaurant'); ?></li>
                        <li id="wxp-menu-3"><?php echo __('Checkout Fields','wxp-restaurant'); ?></li>
                        <li id="wxp-menu-4"><?php echo __('Menu Colors','wxp-restaurant'); ?></li>
                        <li id="wxp-menu-5"><?php echo __('Help / FAQ','wxp-restaurant'); ?></li>
                    </ul>
                </div>
            </div>
            <div class="wxp-s-col-9">
                <div class="wpx-dashboard-content">
                    <div class="wxp-menu-content wxp-menu-1 active">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php echo __('Display Header Message','wxp-restaurant'); ?></th>
                                <td>
                                    <input type="checkbox" name="wxp-res[notice]" value="1" <?php checked('1',0) ?>>
                                    <span class="description">Check if you want to display some message on menu page header.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Header Message','wxp-restaurant'); ?></th>
                                <td><textarea name="wxp-res[g-notice]">Some message for menu page header.</textarea></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Restaurant is closed','wxp-restaurant'); ?></th>
                                <td>
                                    <input type="checkbox" name="wxp-res[is-closed]" value="1" <?php checked('1',0) ?>>
                                    <span class="description">Check if you want to close restaurant for specific day.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Close Message','wxp-restaurant'); ?></th>
                                <td><textarea name="wxp-res[close-notice]">if restaurant is closed, this message will appear on menu page header.</textarea></td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Opening Hours','wxp-restaurant'); ?></th>
                                <td>
                                    <span class="description">Based on opening hours customer can choose delivery day and time on checkout page.</span>
                                    <table class="form-table menu-hours-table">
                                        <tr>
                                            <td><strong><?php echo __('Monday','wxp-restaurant'); ?></strong></td>
                                            <td><label><?php echo __('From','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][monday][from]" value="10:00"></td>
                                            <td><label><?php echo __('To','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][monday][to]" value="23:00"></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo __('Tuesday','wxp-restaurant'); ?></strong></td>
                                            <td><label><?php echo __('From','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][tuesday][from]" value="10:00"></td>
                                            <td><label><?php echo __('To','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][tuesday][to]" value="23:00"></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo __('Wednesday','wxp-restaurant'); ?></strong></td>
                                            <td><label><?php echo __('From','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][wednesday][from]" value="10:00"></td>
                                            <td><label><?php echo __('To','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][wednesday][to]" value="23:00"></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo __('Thursday','wxp-restaurant'); ?></strong></td>
                                            <td><label><?php echo __('From','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][thursday][from]" value="10:00"></td>
                                            <td><label><?php echo __('To','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][thursday][to]" value="23:00"></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo __('Friday','wxp-restaurant'); ?></strong></td>
                                            <td><label><?php echo __('From','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][friday][from]" value="10:00"></td>
                                            <td><label><?php echo __('To','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][friday][to]" value="23:00"></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo __('Saturday','wxp-restaurant'); ?></strong></td>
                                            <td><label><?php echo __('From','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][saturday][from]" value="10:00"></td>
                                            <td><label><?php echo __('To','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][saturday][to]" value="23:00"></td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo __('Sunday','wxp-restaurant'); ?></strong></td>
                                            <td><label><?php echo __('From','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][sunday][from]" value="10:00"></td>
                                            <td><label><?php echo __('To','wxp-restaurant'); ?></label><input type="text" name="wxp-res[days][sunday][to]" value="23:00"></td>
                                        </tr>
                                    </table>
                                    <span class="description"><?php echo __('Put 0 if you want to close restaurant for specific day, e.g. sunday','wxp-restaurant'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" colspan="2">
                                    <input type="hidden" name="wxp-res-save" value="1">
                                    <?php wp_nonce_field('wxp-res-save','wxp-res-admin'); ?>
                                </th>
                                <td><?php submit_button(__('Save Changes','wxp-restaurant'),'primary','wxp-menu-1'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="wxp-menu-content wxp-menu-2">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php echo __('Disable Cart Sidebar','wxp-restaurant'); ?></th>
                                <td>
                                    <input type="checkbox" name="wxp-res[disable-sidebar]" value="1" <?php checked('1',0) ?>>
                                    <span class="description">Check if you want to disable sidebar cart on menu page.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Disable Veg/Non-Veg Filter','wxp-restaurant'); ?></th>
                                <td>
                                    <input type="checkbox" name="wxp-res[disable-veg-filter]" value="1" <?php checked('1',0) ?>>
                                    <span class="description">Check if you want to disable Veg/Non-Veg checkbox filter on menu page.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Disable Searchbar','wxp-restaurant'); ?></th>
                                <td>
                                    <input type="checkbox" name="wxp-res[disable-searchbar]" value="1" <?php checked('1',0) ?>>
                                    <span class="description">Check if you want to disable search bar from menu page header.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td><?php submit_button(__('Save Changes','wxp-restaurant'),'primary','wxp-menu-2'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="wxp-menu-content wxp-menu-3">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php echo __('Display Date Selection','wxp-restaurant'); ?></th>
                                <td>
                                    <input id="wxp-date-select" type="checkbox" name="wxp-res[date]" value="1" <?php checked('1',0) ?>>
                                    <span class="description">Check if you want to enable delivery date selection on checkout page.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Display Time Selection','wxp-restaurant'); ?></th>
                                <td>
                                    <input id="wxp-time-select" type="checkbox" name="wxp-res[time]" value="1" <?php checked('1',0) ?>>
                                    <span class="description">Check if you want to enable delivery time selection on checkout page.</span><br>
                                    <span class="description next-desc"><?php echo __('Delivery date field must be enable to display delivery time field.','wxp-restaurant'); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Timeslot Interval','wxp-restaurant'); ?></th>
                                <td>
                                    <input type="number" name="wxp-res[time-int]" value="" min="1" max="60">
                                    <span class="description">Interval between delivery time slots.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td><?php submit_button(__('Save Changes','wxp-restaurant'),'primary','wxp-menu-3'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="wxp-menu-content wxp-menu-4">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php echo __('Primary Color','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[primary-color]" type='text' class='wxp-color' value='#a1cff4' />
                                    <span class="description">Primary color used on menu page, like category sidebar background etc.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Primary Font Color','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[primary-font-color]" type='text' class='wxp-color' value='#000000' />
                                    <span class="description">Primary font color used on menu page.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Link Color','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[link-color]" type='text' class='wxp-color' value='#ff0000' />
                                    <span class="description">Href tag used on menu page and popup.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Sidebar Cart Icon Color','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[cart-icon-color]" type='text' class='wxp-color' value='#888888' />
                                    <span class="description">Cart icon color.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Sidebar Qty Count Color','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[qty-color]" type='text' class='wxp-color' value='#ff0000' />
                                    <span class="description">Cart Qty Count color in sidebar.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Qty Button Background','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[qty-btn-color]" type='text' class='wxp-color' value='#a1cff4' />
                                    <span class="description">Qty button background color in popup.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Button Color','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[btn-color]" type='text' class='wxp-color' value='#42a3ef' />
                                    <span class="description">Add to cart button color in popup.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Button Text Color','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[btn-txt-color]" type='text' class='wxp-color' value='#ffffff' />
                                    <span class="description">Add to cart button text color in popup.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Header Message Text','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[h-text-color]" type='text' class='wxp-color' value='#000000' />
                                    <span class="description">Header message text color on menu page.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Header Message Background','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[h-bg-color]" type='text' class='wxp-color' value='#5cf0f2' />
                                    <span class="description">Header message background color on menu page.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Close Message Text','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[c-text-color]" type='text' class='wxp-color' value='#c1bdbd' />
                                    <span class="description">Restaurant close message text color on menu page.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php echo __('Close Message Background','wxp-restaurant'); ?></th>
                                <td>
                                    <input name="wxp-res[c-bg-color]" type='text' class='wxp-color' value='#f2b25c' />
                                    <span class="description">Restaurant close message background color on menu page.</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td><?php submit_button(__('Save Changes','wxp-restaurant'),'primary','wxp-menu-4'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="wxp-menu-content wxp-menu-5">
                        <ul class="wxp-help">
                            <li class="wxp-hq">WC Restaurant free plugin not working as expected on my site.</li>
                            <li class="wxp-ha">Please raise a topic/issue at support forum on wordpress.org or write us directly <a target="_blank" href="https://wooexperts.com/contact-us/">here</a>.</li>

                            <li class="wxp-hq">WC Restaurant Pro plugin not working as expected on my site.</li>
                            <li class="wxp-ha">Please write us directly <a target="_blank" href="https://wooexperts.com/contact-us/">here</a> and don't raise any issue at wordpress.org</li>
                            <li class="wxp-ha">You can get pro version help only by connecting us directly.</li>

                            <li class="wxp-hq">How can i display menu on front end ?</li>
                            <li class="wxp-ha">Use shortcode <strong>[wxp_restaurant]</strong> on any page to insert menu.</li>

                            <li class="wxp-hq">What is shortcode for pro version ?</li>
                            <li class="wxp-ha">Shortcode <strong>[wxp_restaurant]</strong> is same for both free and pro version.</li>

                            <li class="wxp-hq">How can i activate pro version ?</li>
                            <li class="wxp-ha">Remove the free version from your site and install and activate the pro version.</li>

                            <li class="wxp-hq">I want additional functionality / features in WC Restaurant plugin.</li>
                            <li class="wxp-ha">You can contact us <a target="_blank" href="https://wooexperts.com/contact-us/">here</a>.</li>

                            <li class="wxp-hq">Does this plugin support variable products?</li>
                            <li class="wxp-ha">Yes, this plugin support variable products.</li>

                            <li class="wxp-hq">How can i add additional product options?</li>
                            <li class="wxp-ha">No, You can't add in free version, but pro version support <a target="_blank" href="https://wordpress.org/plugins/woocommerce-product-addon/">PPOM for WooCommerce</a> and you can add additional product options using PPOM Plugin.</li>

                            <li class="wxp-hq">Product categories are missing on menu page.</li>
                            <li class="wxp-ha">Make sure all categories you want to display in menu are setup as parent categories, child categories may not appear correctly.</li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
