/**
 * @author     朱羽婷
 */
$(document).ready(function(){
	//下一页
	window.nextpage = function(e)
	{
		page = $(".page").find(".check").html();
		max_page = $(e).attr("data_max");
		page = page*1 + 1;
		if(max_page !== undefined)
		{
			if(page > max_page)
			{
				page = max_page;
			}
		}

		changepage(page);
	}

	//上一页
	window.prevpage = function()
	{
		page = $(".page").find(".check").html();
		page = page*1 - 1;
		if(page < 1 )
		{
			page = 1;
		}
		changepage(page);
	}

	//评论分页
	window.changepage = function (e)
	{
		status = $(".checked").attr('name');

		//获取商品评价信息
		$.post(SITE_URL  + '?ctl=Goods_Goods&met=getGoodsEvaluationList&typ=json',{goods_id:goods_id,status:status,page:e},function(data)
		 {
		 console.info(data);
		 if(data && 200 == data.status) {
			 $(".explain").html("");
			 $(".page").html("");
			 $(".goods_next").attr('data_max',data.data.total);
		 	if(data.data.items == '')
		 	{
		 		$(".explain").append(_('暂无评论'));
		 	}else
		 	{
		 		for(var i in data.data.items)
		 		{
					 console.info(data.data.items[i]);
					 star_str = '';
					 for(s=1;s<=data.data.items[i].scores;s++)
					 {
					 star_str = star_str + '<em class="em_1"></em>';
					 }
					 spec_str = '<p>';
					 for(var g in data.data.items[i].goods_spec)
					 {
					 spec_str = spec_str + '<span>'+data.data.items[i].goods_spec[g]+'</span>';
					 }
					 spec_str = spec_str + '</p>';

					 str = '<div class="comment clearfix"><div class="detaildiv_1 "><p>'+star_str+'</p>';
					 str = str + '<p>下单'+ data.data.items[i].diff_time +'天后评论</p><time>' + data.data.items[i].create_time + '</time>'+spec_str+'</div>';
					 str = str + '<div class="detaildiv_2  "><p>'+data.data.items[i].content+'</p></div>';
					 str = str + '<div class="detaildiv_3 "><p> <img src="'+data.data.items[i].user_grade_logo+'"></p></div><p>'+data.data.items[i].user_name+'</p><p>'+data.data.items[i].user_grade_name+'</p></div>';

					 $(".explain").append(str);

				}

				 page_str = '';
				 if(data.data.total >= 3 )
				 {
					 if(data.data.page <= 3)
					 {
						 for(i=1;i<=3;i++)
						 {
							 if(data.data.page == i)
							 {
							 	page_str += '<a class="active1 check" onclick="changepage('+i+')">'+i+'</a>';
							 }
							 else
							 {
								 page_str += '<a class="active1" onclick="changepage('+i+')">'+i+'</a>';
							 }

						 }
						 page_str += '...<a  class="active1" onclick="changepage('+data.data.total+')">'+data.data.total+'</a>';
					 }
					 else if(data.data.page >= data.data.total-3)
					 {
						 page_str += '<a class="active1" onclick="changepage(1)">1</a>...';
						 for(i=data.data.total-3;i<=data.data.total;i++)
						 {
							 if(data.data.page == i)
							 {
							 	page_str += '<a  class="active1 check" onclick="changepage('+i+')">'+i+'</a>';
							 }
							 else
							 {
							 	page_str += '<a class="active1" onclick="changepage('+i+')">'+i+'</a>';
							 }

						 }

					 }
					 else
					 {
						 page_str += '<a class="active1" onclick="changepage(1)">1</a>...';
						 for(i=data.data.page-2;i<=data.data.page*1+2;i++)
						 {
							 if(data.data.page == i)
							 {
							 	page_str += '<a class="active1 check" onclick="changepage('+i+')">'+i+'</a>';
							 }
							 else
							 {
							 	page_str += '<a href="#" class="active1" onclick="changepage('+i+')">'+i+'</a>';
							 }

						 }
						 page_str += '...<a class="active1" onclick="changepage('+data.data.total+')">'+data.data.total+'</a>';
					 }
				 }
				 else
				 {
					 for(i=1;i<=data.data.total;i++)
					 {
					 if(data.data.page == i)
					 {
					 page_str += '<a  class="active1 check" onclick="changepage('+i+')">'+i+'</a>';
					 }
					 else
					 {
					 page_str += '<a class="active1" onclick="changepage('+i+')">'+i+'</a>';
					 }

					 }
				 }

			 $(".page").append(page_str);
			 }

		 	}
			 else
			 {
				$(".explain").append('获取数据失败');
			 }
		 }
		 );
	}

})
