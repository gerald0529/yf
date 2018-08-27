$.extend($.fn,{
        //懒人建站 http://www.51xuediannao.com/
        fnTimeCountDown:function(d){
            this.each(function(){
                var $this = $(this);
                var o = {
                   
                    sec: $this.find(".sec"),
                    mini: $this.find(".mini"),
                    hour: $this.find(".hour"),
                    day: $this.find(".day")
                  
                };
                var f = {
                    zero: function(n){
                        var _n = parseInt(n, 10);//解析字符串,返回整数
                        if(_n > 0){
                            if(_n <= 9){
                                //_n = "0" + _n
                                _n = _n
                            }
                            return String(_n);
                        }else{
                            return "0";
                        }
                    },
                    dv: function(){
                        //d = d || Date.UTC(2050, 0, 1); //如果未定义时间，则我们设定倒计时日期是2050年1月1日
                        var _d = $this.data("end") || d;
						
                        var now = new Date(),
                            endDate = new Date(Date.parse(_d.replace(/-/g, "/")));
							if(endDate < now)
								return false;
                       
                        var dur = (endDate - now.getTime()) / 1000 , mss = endDate - now.getTime() ,pms = {
                            sec: "0",
                            mini: "0",
                            hour: "0",
                            day: "0",
                        };
                        if(mss > 0)
						{
                            pms.sec = f.zero(dur % 60);
                            pms.mini = Math.floor((dur / 60)) > 0? f.zero(Math.floor((dur / 60)) % 60) : "0";
                            pms.hour = Math.floor((dur / 3600)) > 0? f.zero(Math.floor((dur / 3600)) % 24) : "0";
                            pms.day = Math.floor((dur / 86400)) > 0? f.zero(Math.floor((dur / 86400))) : "0"; 
                        }
						else
						{
                            pms.day=pms.hour=pms.mini=pms.sec="0";
                            //alert('结束了');
                            return;
                        }
                        return pms;
                    },
                    ui: function(){
                        if(o.sec){
                            o.sec.html(f.dv().sec);
                        }
                        if(o.mini){
                            o.mini.html(f.dv().mini);
                        }
                        if(o.hour){
                            o.hour.html(f.dv().hour);
                        }
                        if(o.day){
                            o.day.html(f.dv().day);
                        }
                        setTimeout(f.ui, 1);
                    }
                };
                f.ui();
            });
        }
    });