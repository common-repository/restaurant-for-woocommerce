jQuery(function($){

    var cont_in = $('.wxp-menu-item-in');
    var side_menu = $('ul.wxp-menu');
    var search_data = $(document).find('.wxp-menu-data').attr('data-menu');
    var item_obj = jQuery.parseJSON(search_data);

    var wxp_menu = {
        init:function(){
            $(document).on('scroll',this.wxp_scroll);
            $(document).on('click','ul.wxp-menu li.menu-cat',{view:this},this.wxp_menu_slide);
            $(document).on('input','input[name="wxp-search-item"]',{view:this},this.wxp_search_by_txt);
            $(document).on('change','input[type="checkbox"].wxp-item-filter',{view:this},this.wxp_search_by_check);
            $(document).on('click','.wxp-search-item span.search-clear',{view:this},this.wxp_clear_search);
            $(document).on('click','.wxp-item-btn',{view:this},this.wxp_item_customization);
            $(document).on('click','.wxp-c-inn .quantity input[type="button"]',{view:this},this.wxp_item_qty);
            $(document).on('click','.wxp-c-inn .single_add_to_cart_button',{view:this},this.add_to_cart);
            $(document).on('click','.wxp-side-icon',{view:this},this.wxp_cart_show);
            $(document).on('click','.wxpmenu-nav .close-wxp-cart',{view:this},this.wxp_cart_close);
            $(document).on('click','.wxpmenu-nav .remove-item',{view:this},this.wxp_item_rm);
            $(document).on('change','select.wxp-var-select',{view:this},this.wxp_set_var_opts);
            $(document).on('click','.opt-clear-vars',{view:this},this.wxp_clear_var_opts);
            $(document).on('change','select.wxp-cat-select',{view:this},this.wxp_cat_nav);
            var items_found = wxp_menu.wxp_menu_filter();
            wxp_menu.wxp_set_result(items_found);
        },
        wxp_block:function(ele='.wxp-menu-container'){
            $(ele).addClass('wxp-loading').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },
        wxp_unblock:function(ele='.wxp-menu-container'){
            $(ele).removeClass('wxp-loading').unblock();
        },
        wxp_update:function(obj){
            if(obj.hasOwnProperty("fragments")){
                $.each(obj.fragments, function(key,value){
                    $(key).replaceWith(value);
                });
            }
        },
        wxp_cart_show:function(){
            $('.wxpmenu-nav').addClass('in');
        },
        wxp_cart_close:function(){
            $(this).closest('.wxpmenu-nav').toggleClass('in');
        },
        wxp_menu_slide:function(){
            var slug = $(this).attr('data-id');
            $('html,body').animate({
                scrollTop: $(".wxp-sec-"+slug).offset().top-10
            },800);
        },
        wxp_qty:function($this){
            var qty_top = $this.closest('.wxp-item-btn');
            var qty = qty_top.attr('data-qty');
            if(!qty_top.hasClass('qty-added')){
                qty_top.addClass('qty-added');
            }
            qty++;
            qty_top.find('.wxp-btn-qty').text(qty);
            qty_top.attr('data-qty',qty);
        },
        wxp_qty_minus:function($this){
            var qty_top = $this.closest('.wxp-item-btn');
            var qty = qty_top.attr('data-qty');
            qty--;
            if(qty){
                qty_top.find('.wxp-btn-qty').text(qty);
            }
            else
            {
                qty_top.removeClass('qty-added');
                qty_top.find('.wxp-btn-qty').text('Add');
            }
            qty_top.attr('data-qty',qty);
        },
        wxp_set_result:function(items){
            var found = false;
            $(document).find('.wxp-no-result').remove();
            $.each(items,function(cat,count){
                var cat_sec = $('ul.wxp-menu li.wxp-menu-'+cat);
                var item_sec = $('.wxp-menu-item-in div.wxp-sec-'+cat);
                if(count){
                    found = true;
                    cat_sec.find('span.menu-cat-count').text('('+count+')');
                    cat_sec.show();
                    item_sec.show();
                }
                else
                {
                    cat_sec.hide();
                    item_sec.hide();
                }
            });
            if(!found){
                $('#wxp-menu-cat').append('<div class="wxp-no-result no-result-side"></div>');
                $('.wxp-menu-item-in').append('<div class="wxp-no-result no-result-main"><span class="wxp-icon"></span>'+wxpmenu.wxp_no_item+'</div>');
            }
        },
        wxp_menu_filter:function(){
            var search_box = $('input[name="wxp-search-item"]');
            var items_found = {};
            var text_in = search_box.length ? search_box.val().trim().toLowerCase() : '',
                is_veg = $('#wxp-is-veg').prop("checked"),
                is_nonveg = $('#wxp-is-nonveg').prop("checked");

            $.each(item_obj,function(cat,items){
                items_found[cat] = 0;
                for(var i=0;i<items.length;i++){
                    var in_menu = false;
                    if('title' in items[i]){
                        if(text_in!=='' || is_veg || is_nonveg){

                            if((text_in!=='' && is_veg) || (text_in!=='' && is_nonveg)){

                                if(items[i].title.toLowerCase().indexOf(text_in) >= 0 && is_veg && items[i].veg){
                                    in_menu = true;
                                }
                                else if(items[i].desc.toLowerCase().indexOf(text_in) >= 0 && is_veg && items[i].veg){
                                    in_menu = true;
                                }
                                else if(items[i].title.toLowerCase().indexOf(text_in) >= 0 && is_nonveg && items[i].non_veg){
                                    in_menu = true;
                                }
                                else if(items[i].desc.toLowerCase().indexOf(text_in) >= 0 && is_nonveg && items[i].non_veg){
                                    in_menu = true;
                                }

                            }
                            else if(text_in!=='' && !is_veg && !is_nonveg){
                                if(items[i].title.toLowerCase().indexOf(text_in) >= 0){
                                    in_menu = true;
                                }
                                else if(items[i].desc.toLowerCase().indexOf(text_in) >= 0){
                                    in_menu = true;
                                }
                            }
                            else if(text_in==='' && is_veg){
                                in_menu = items[i].veg;
                            }
                            else if(text_in==='' && is_nonveg){
                                in_menu = items[i].non_veg;
                            }

                            if(in_menu){
                                $('.wxp-item-'+items[i].id).show();
                                items_found[cat]++;
                            }
                            else
                            {
                                $('.wxp-item-'+items[i].id).hide();
                            }
                        }
                        else
                        {
                            $('.wxp-item-'+items[i].id).show();
                            items_found[cat]++;
                        }
                    }
                }
            });
            return items_found;
        },
        wxp_search_by_txt:function(){
            var items_found = wxp_menu.wxp_menu_filter();
            wxp_menu.wxp_set_result(items_found);
        },
        wxp_search_by_check:function(){
            $('input[type="checkbox"].wxp-item-filter').not(this).prop("checked",false);
            var items_found = wxp_menu.wxp_menu_filter();
            wxp_menu.wxp_set_result(items_found);
        },
        wxp_clear_search:function(){
            $('input[name="wxp-search-item"]').val('');
            var items_found = wxp_menu.wxp_menu_filter();
            wxp_menu.wxp_set_result(items_found);
        },
        wxp_scroll:function(){
            var cutoff = $(window).scrollTop();
            $('.wxp-items-sec').removeClass('top').each(function(){
                if ((($(this).offset().top+$(this).height())-cont_in.find('.wxp-item-head.active').height()) > cutoff) {
                    $(this).addClass('top');
                    cont_in.find('.wxp-item-head.active').removeClass('active');
                    cont_in.find('.wxp-items-sec.top').prev('.wxp-item-head').addClass('active');
                    var side_cat = cont_in.find('.wxp-item-head.active').attr('data-slug');
                    side_menu.find('li').removeClass('active');
                    side_menu.find('li#menu-cat-'+side_cat).addClass('active');
                    return false;
                }
            });
        },
        wxp_set_param:function(uri, key, value){
            var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        },
        wxp_item_qty:function(){
            var qty = $(this).closest('.quantity').find('.qty').val();
            qty = parseInt(qty);
            var plus = $(this).hasClass('plus') === true;
            qty = plus ? qty+1 : qty-1;
            if(qty>0){
                $(this).closest('.quantity').find('.qty').val(qty);
            }
        },
        wxp_get_item_options:function(el){

            var html = '';
            var options = el.closest('.wxp-item-btn-top').find('.wxp-item-opts').length;
            if(options){
                var props = el.closest('.wxp-item-btn-top').find('.wxp-item-opts').attr('data-product');
                var props_opts = JSON.parse(props);
                var price_pre = props_opts.variable ? 'From ' : '';

                html+='<div class="wxp-c-inn"><form method="post" class="wxp-menu-frm" id="wxp-menu-frm">';
                html+='<div class="wxp-c-title wxp-row"><h4>'+props_opts.title+'<span class="wxp-pre-price">('+price_pre+props_opts.currency+'<span class="wxp-price">'+props_opts.price+'</span>)</span></h4></div>';
                html+='<div class="wxp-row-info"><div class="wxp-col-12"><div class="wxp-menu-info"></div></div></div>';

                var opts = el.closest('.wxp-item-btn-top').find('.wxp-item-opts').attr('data-options');
                var pro_opts = JSON.parse(opts);
                if(pro_opts && props_opts.variable){
                    if(pro_opts.hasOwnProperty("data")){
                        for(var key in pro_opts.data){
                            if(pro_opts.data.hasOwnProperty(key)){
                                var attr_key = key.toLowerCase();
                                html+='<div class="wxp-opt-item wxp-row" data-require="true" data-type="select">';
                                html+='<div class="wxp-col-4"><label>'+key.replace("pa_","")+'</label></div>';
                                html+='<div class="wxp-col-8 wxp-pro-opt">';
                                html+='<select name="opts['+attr_key+']" data-attr="'+attr_key+'" data-id="'+props_opts.id+'" class="wxp-menu-opt wxp-var-select '+attr_key+'">';
                                html+='<option value="">'+wxpmenu.wxp_select+'</option>';
                                $.each(pro_opts.data[key],function(index,value){
                                    html+='<option value="'+value+'">'+value+'</option>';
                                });
                                html+='</select>';
                                html+='<div class="opt-desc"></div>';
                                html+='</div>';
                                html+='</div>';
                            }
                        }

                        html+='<div class="wxp-row var-clear-row">';
                        html+='<div class="wxp-col-4"></div>';
                        html+='<div class="wxp-col-8">';
                        html+='<div class="opt-clear-vars"><span>'+wxpmenu.wxp_clear+'</span></div>';
                        html+='</div>';
                        html+='</div>';
                    }
                }
                html+='<div class="wxp-row">';
                html+='<div class="wxp-col-4">';
                html+='<div class="quantity buttons_added">';
                html+='<input type="button" value="-" class="minus">';
                html+='<input type="number" step="1" min="1" name="quantity" value="1" class="input-text qty" size="4">';
                html+='<input type="button" value="+" class="plus">';
                html+='</div>';
                html+='</div>';
                html+='<div class="wxp-col-8">';
                html+='<input type="hidden" name="product" id="product_id" value="'+props_opts.id+'">';
                html+='<input type="hidden" name="variation" id="variation_id" value="0">';
                html+='<button type="button" class="single_add_to_cart_button button btn">'+wxpmenu.wxp_add_to_cart+'</button>';
                html+='</div>';
                html+='</div>';

                html+='</form></div>';
            }
            return {".wxp-c-inn":html};
        },
        wxp_item_customization:function(e){

            var $this = $(this);
            var options = parseInt($this.attr('data-options'));
            if(options){
                $.fancybox.open({
                    type: "html",
                    src:
                        '<div class="wxp-customization-main">'+
                        '<div class="wxp-c-main"><span class="wxp-icon wxp-c-close" data-fancybox-close></span></div>' +
                        '<div class="wxp-c-inn"></div>' +
                        '</div>',
                    opts: {
                        animationDuration: 10,
                        animationEffect: "material",
                        modal: true,
                        baseTpl:
                            '<div class="fancybox-container fc-container" role="dialog" tabindex="-1">' +
                            '<div class="fancybox-bg"></div>' +
                            '<div class="fancybox-inner">' +
                            '<div class="fancybox-stage"></div>' +
                            "</div>" +
                            "</div>",
                        afterShow:function(instance,current,e){
                            var opts_html = wxp_menu.wxp_get_item_options($this);
                            $.each(opts_html,function(key,value){
                                $(key).replaceWith(value);
                            });
                        },
                        beforeShow:function(instance,current,e){
                            $("body").css("overflow","hidden");
                        },
                        beforeClose:function(instance,current,e){
                            $("body").css("overflow","auto");
                        }
                    }
                });
            }
            else
            {
                var qty = $this.attr('data-qty'),
                    id = $this.attr('data-id');
                qty++;
                var $data  = 'product='+id+'&quantity='+qty+'&opt='+false;
                wxp_menu.wxp_block();
                wxp_menu.wxp_update_cart($data);
            }
        },
        add_to_cart:function(e){
            var process = true;
            var paased = true;
            if($(document).find('form.wxp-menu-frm').length && process){
                $(document).find('form.wxp-menu-frm div.wxp-opt-item').each(function(){

                    paased = true;
                    var type = $(this).attr('data-type');
                    var max = parseInt($(this).attr('data-max'));
                    var min = parseInt($(this).attr('data-min'));
                    var require = $(this).attr('data-require') === 'true';
                    $(this).find('.wxp-pro-opt .wxp-war').remove();

                    if(type==='checkbox' || type==='radio'){
                        var checked = $(this).find('div.wxp-pro-opt input:checked').length;
                        if(require){
                            if(min && !checked){
                                $(this).find('.wxp-pro-opt').append('<div class="wxp-war">'+'Minimum '+min+' options required.'+'</div>');
                                paased = false;
                            }
                            else if(!min && !checked){
                                $(this).find('.wxp-pro-opt').append('<div class="wxp-war">'+'Minimum 1 option required.'+'</div>');
                                paased = false;
                            }
                        }
                        if(min && checked<min){
                            $(this).find('.wxp-pro-opt').append('<div class="wxp-war">'+'Minimum '+min+' options required.'+'</div>');
                            paased = false;
                        }
                        if(max && checked>max){
                            $(this).find('.wxp-pro-opt').append('<div class="wxp-war">'+'Maximum '+max+' options allowed.'+'</div>');
                            paased = false;
                        }
                    }
                    else if(type==='select'){
                        require = true;
                        var selected = $(this).find('div.wxp-pro-opt select').val();
                        if(require && selected===''){
                            $(this).find('.wxp-pro-opt').append('<div class="wxp-war">'+wxpmenu.wxp_select_opt+'</div>');
                            paased = false;
                        }
                    }
                    else if(type==='text'){
                        var selected = $(this).find('div.wxp-pro-opt input[type="text"]').val().trim();
                        if(require && selected===''){
                            $(this).find('.wxp-pro-opt').append('<div class="wxp-war">This field is required.</div>');
                            paased = false;
                        }
                    }
                    else if(type==='textarea'){
                        var selected = $(this).find('div.wxp-pro-opt textarea').val().trim();
                        if(require && selected===''){
                            $(this).find('.wxp-pro-opt').append('<div class="wxp-war">This field is required.</div>');
                            paased = false;
                        }
                    }
                    if(!paased){
                        process = false;
                        return false;
                    }
                });
            }
            if(process){
                $('.wxp-menu-info').html('');
                $('form.wxp-menu-frm .single_add_to_cart_button').prop('disabled',true);
                var $data = $('form.wxp-menu-frm').serialize();
                wxp_menu.wxp_update_cart($data);
            }
        },
        wxp_update_cart:function($data){
            $.ajax({
                type	: "POST",
                cache	: false,
                async: true,
                url     : wxp_menu.wxp_set_param(wxpmenu.wxp_ajax,'wexperts-ajax','add_to_cart'),
                dataType : 'json',
                data: {
                    'data' : $data,
                    'check' : wxpmenu.wxp_nonce,
                },
                success: function(response){
                    wxp_menu.wxp_unblock();
                    if(response.res){
                        $('.wxp-menu-info').html('<div class="wxp-info">'+wxpmenu.wxp_added_to_cart+'</div>');
                        setTimeout(function(){
                            $.fancybox.close();
                        },1500);
                        wxp_menu.wxp_update(response);
                    }
                    else
                    {
                        $('.wxp-menu-info').html('<div class="wxp-war">Something went wrong!</div>');
                    }
                }
            });
        },
        wxp_item_rm:function(){
            wxp_menu.wxp_block('.wxp-cart-in');
            $.ajax({
                type	: "POST",
                cache	: false,
                async: true,
                url     : wxp_menu.wxp_set_param(wxpmenu.wxp_ajax,'wexperts-ajax','remove_item'),
                dataType : 'json',
                data: {
                    'id' : $(this).attr('data-id'),
                    'check' : wxpmenu.wxp_nonce,
                },
                success: function(response){
                    wxp_menu.wxp_unblock();
                    wxp_menu.wxp_unblock('.wxp-cart-in');
                    wxp_menu.wxp_update(response);
                }
            });
        },
        wxp_get_opts:function(){
            var matching = {};
            $('form.wxp-menu-frm').find('select.wxp-var-select').each(function(i){
                var type = $(this).attr('data-attr');
                matching['attribute_'+type] = $(this).val();
            });
            return matching;
        },
        wxp_ismatch:function(variation_attributes,attributes){
            var match = true;
            for(var attr_name in variation_attributes){
                if(variation_attributes.hasOwnProperty(attr_name)){
                    var val1 = variation_attributes[attr_name];
                    var val2 = attributes[attr_name];
                    if(val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2){
                        match = false;
                    }
                }
            }
            return match;
        },
        wxp_matching_opts:function(variations,attributes){
            var matching = [];
            for(var i = 0; i < variations.length; i++){
                var variation = variations[i];

                if(wxp_menu.wxp_ismatch(variation.attributes,attributes)){
                    matching.push(variation);
                }
            }
            return matching;
        },
        wxp_set_var_opts:function(){

            var p_id = $(this).attr('data-id'),
                opts = $(document).find('.wxp-item-'+p_id+' .wxp-item-opts').attr('data-options');
            var pro_opts = JSON.parse(opts);

            if(pro_opts.options.length){
                wxp_menu.wxp_update_select(p_id);
            }
        },
        wxp_update_select:function(p_id){

            var opts = $(document).find('.wxp-item-'+p_id+' .wxp-item-opts').attr('data-options');
            var pro_opts = JSON.parse(opts);

            var currentAttributes = wxp_menu.wxp_get_opts();

            $('form.wxp-menu-frm').find('select.wxp-var-select').each(function(index,el){

                var current_attr_select     = $(el),
                    current_attr_name       = 'attribute_'+current_attr_select.attr('data-attr'),
                    show_option_none        = 'yes',
                    option_gt_filter        = ':gt(0)',
                    attached_options_count  = 0,
                    new_attr_select         = $('<select/>'),
                    selected_attr_val       = current_attr_select.val() || '',
                    selected_attr_val_valid = true;

                // Reference options set at first.
                if ( ! current_attr_select.data( 'attribute_html' ) ) {
                    var refSelect = current_attr_select.clone();

                    refSelect.find( 'option' ).prop( 'disabled attached', false ).prop( 'selected', false );

                    // Legacy data attribute.
                    current_attr_select.data(
                        'attribute_options',
                        refSelect.find( 'option' + option_gt_filter ).get()
                    );
                    current_attr_select.data( 'attribute_html', refSelect.html() );
                }

                new_attr_select.html( current_attr_select.data( 'attribute_html' ) );

                var checkAttributes = $.extend( true, {}, currentAttributes );

                checkAttributes[ current_attr_name ] = '';

                var variations = wxp_menu.wxp_matching_opts(pro_opts.options,checkAttributes);

                // Loop through variations.
                for(var num in variations ){
                    if(variations.hasOwnProperty(num)){
                        if ( typeof( variations[ num ] ) !== 'undefined' ) {
                            var variationAttributes = variations[ num ].attributes;

                            for ( var attr_name in variationAttributes ) {
                                if ( variationAttributes.hasOwnProperty( attr_name ) ) {
                                    var attr_val         = variationAttributes[ attr_name ],
                                        variation_active = '';

                                    if ( attr_name === current_attr_name ) {

                                        variation_active = 'enabled';

                                        if ( attr_val ) {
                                            // Decode entities.
                                            attr_val = $( '<div/>' ).html( attr_val ).text();

                                            // Attach to matching options by value. This is done to compare
                                            // TEXT values rather than any HTML entities.
                                            var $option_elements = new_attr_select.find( 'option' );
                                            if ( $option_elements.length ) {
                                                for (var i = 0, len = $option_elements.length; i < len; i++) {
                                                    var $option_element = $( $option_elements[i] ),
                                                        option_value = $option_element.val();

                                                    if ( attr_val === option_value ) {
                                                        $option_element.addClass( 'attached ' + variation_active );
                                                        break;
                                                    }
                                                }
                                            }
                                        } else {
                                            // Attach all apart from placeholder.
                                            new_attr_select.find( 'option:gt(0)' ).addClass( 'attached ' + variation_active );
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // Count available options.
                attached_options_count = new_attr_select.find( 'option.attached' ).length;

                // Check if current selection is in attached options.
                if ( selected_attr_val ) {
                    selected_attr_val_valid = false;

                    if ( 0 !== attached_options_count ) {
                        new_attr_select.find( 'option.attached.enabled' ).each( function() {
                            var option_value = $( this ).val();

                            if ( selected_attr_val === option_value ) {
                                selected_attr_val_valid = true;
                                return false; // break.
                            }
                        });
                    }
                }

                // Detach the placeholder if:
                // - Valid options exist.
                // - The current selection is non-empty.
                // - The current selection is valid.
                // - Placeholders are not set to be permanently visible.
                if ( attached_options_count > 0 && selected_attr_val && selected_attr_val_valid && ( 'no' === show_option_none ) ) {
                    new_attr_select.find( 'option:first' ).remove();
                    option_gt_filter = '';
                }

                // Detach unattached.
                new_attr_select.find( 'option' + option_gt_filter + ':not(.attached)' ).remove();

                // Finally, copy to DOM and set value.
                current_attr_select.html( new_attr_select.html() );
                current_attr_select.find( 'option' + option_gt_filter + ':not(.enabled)' ).prop( 'disabled', true );

                // Choose selected value.
                if ( selected_attr_val ) {
                    // If the previously selected value is no longer available, fall back to the placeholder (it's going to be there).
                    if ( selected_attr_val_valid ) {
                        current_attr_select.val( selected_attr_val );
                    } else {
                        current_attr_select.val( '' ).trigger( 'change' );
                    }
                } else {
                    current_attr_select.val( '' ); // No change event to prevent infinite loop.
                }
            });
            wxp_menu.update_var_data(pro_opts.options);
        },
        wxp_get_chosen:function(){
            var data   = {};
            var count  = 0;
            var chosen = 0;

            $('form.wxp-menu-frm').find('select.wxp-var-select').each( function() {
                var attribute_name = 'attribute_'+ $(this).attr('data-attr');
                var value          = $(this).val() || '';

                if(value.length > 0){
                    chosen ++;
                }

                count ++;
                data[attribute_name] = value;
            });

            return {
                'count'      : count,
                'chosenCount': chosen,
                'data'       : data
            };
        },
        update_var_data:function(options){
            var $form = $('form.wxp-menu-frm');
            $('.opt-clear-vars').show();
            var variation = wxp_menu.wxp_get_chosen();
            if(variation.chosenCount===variation.count){
                var variations = wxp_menu.wxp_matching_opts(options,variation.data);
                if(variations.length && variations.length===1){
                    for(var num in variations){
                        if(variations.hasOwnProperty(num)){
                            $form.find('span.wxp-price').text(variations[num].price);
                            $form.find('#variation_id').val(variations[num].variation_id);
                        }
                    }
                }
            }
        },
        wxp_clear_var_opts:function(){
            var $form = $('form.wxp-menu-frm');
            var product_id = $(this).closest('form.wxp-menu-frm').find('#product_id').val();
            if(product_id){
                var opts = $(document).find('.wxp-item-'+product_id+' .wxp-item-opts').attr('data-product');
                var props_opts = JSON.parse(opts);
                $form.find('span.wxp-price').text(props_opts.price);
            }
            $form.find('select.wxp-var-select').val('').trigger('change');
            $('.opt-clear-vars').hide();
        },
        wxp_cat_nav:function(){
            var cat = $(this).val();
            if(cat!==''){
                $('html,body').animate({
                    scrollTop: $(document).find(".wxp-menu-item-in .wxp-sec-"+cat).offset().top
                },2000);
            }
        },
    };
    wxp_menu.init();

});