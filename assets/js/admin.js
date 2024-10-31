jQuery(function($){
    var wxp_admin = {
        init:function(){
            $(document).on('click','ul.wxp-admin-nav li',{view:this},this.wxp_admin_menu);
            $(document).on('change','#wxp-date-select',{view:this},this.wxp_admin_date);
            wxp_admin.after_init();
        },
        after_init:function(){
            $(".wxp-color").spectrum({
                preferredFormat: "hex",
                showInput: true,
                disabled: true
            });
            var side_height = $('.wpx-dashboard-side').height(),
                db_height = $('.wpx-dashboard-content').height();
            if(db_height>side_height){
                $('.wpx-dashboard-side').height(db_height);
            }
            $('.wpx-dashboard-content *').prop('disabled',true);
        },
        wxp_admin_menu:function(){
            var id = $(this).attr('id');
            $('ul.wxp-admin-nav li').removeClass('active');
            $(this).addClass('active');

            $('.wpx-dashboard-content .wxp-menu-content').removeClass('active');
            $('.wpx-dashboard-content .'+id).addClass('active');
        },
        wxp_admin_date:function(){
            if(!$(this).prop("checked")){
                $('#wxp-time-select').prop("checked",false);
            }
        }
    };
    wxp_admin.init();
});