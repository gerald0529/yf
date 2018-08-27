function initField()
{
    $.get('./index.php?ctl=Main&met=getMainInfo&typ=json',function(a){
        if(a.status==200)
        {
            var b= a.data;
            console.log(b.month_member);
            b.member_count!=0?$('#statistics_member').html(b.member_count):$('#statistics_member').parent().hide();
            b.week_member!=0?$('#statistics_week_add_member').html(b.week_member):$('#statistics_week_add_member').parent().hide();
            b.month_member!=0?$('#statistics_month_add_member').html(b.month_member):$('#statistics_month_add_member').parent().hide();

			//商品
            b.goods_num!=0?$('#statistics_goods').html(b.goods_num):$('#statistics_goods').parent().hide();
            b.week_goods_num!=0?$('#statistics_week_add_product').html(b.week_goods_num):$('#statistics_week_add_product').parent().hide();
            b.verify_goods_num!=0?$('#statistics_product_verify').html(b.verify_goods_num):$('#statistics_product_verify').parent().hide();
            b.report_num!=0?$('#statistics_inform_list').html(b.report_num):$('#statistics_inform_list').parent().hide();
            b.goods_brands_num!=0?$('#statistics_brand_apply').html(b.goods_brands_num):$('#statistics_brand_apply').parent().hide();
			
			//店铺
            b.shop_nums!=0?$('#statistics_store').html(b.shop_nums):$('#statistics_store').parent().hide();
            b.verify_shop_nums!=0?$('#statistics_store_joinin').html(b.verify_shop_nums):$('#statistics_store_joinin').parent().hide();
            b.shop_class_nums!=0?$('#statistics_store_bind_class_applay').html(b.shop_class_nums):$('#statistics_store_bind_class_applay').parent().hide();
            b.renewal_nums!=0?$('#statistics_store_reopen_applay').html(b.renewal_nums):$('#statistics_store_reopen_applay').parent().hide();
            b.shop_expired_nums!=0?$('#statistics_store_expired').html(b.shop_expired_nums):$('#statistics_store_expired').parent().hide();
            b.shop_expire_nums!=0?$('#statistics_store_expire').html(b.shop_expire_nums):$('#statistics_store_expire').parent().hide();
			
			//交易
            b.order_nums!=0?$('#statistics_order').html(b.order_nums):$('#statistics_order').parent().hide();
            b.physical_return_nums!=0?$('#statistics_refund').html(b.physical_return_nums):$('#statistics_refund').parent().hide();
            b.physical_return_goods_nums!=0?$('#statistics_return').html(b.physical_return_goods_nums):$('#statistics_return').parent().hide();
            b.virtual_return_goods_nums!=0?$('#statistics_vr_refund').html(b.virtual_return_goods_nums):$('#statistics_vr_refund').parent().hide();
            b.complain_nums!=0?$('#statistics_complain_new_list').html(b.complain_nums):$('#statistics_complain_new_list').parent().hide();
            b.handle_nums!=0?$('#statistics_complain_handle_list').html(b.handle_nums):$('#statistics_complain_handle_list').parent().hide();
        }
    });
}

initField();