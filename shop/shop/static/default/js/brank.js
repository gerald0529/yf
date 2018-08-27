  function doMove(obj,attr,speed,target,callBack){
     if(obj.timer) return;
        var ww=obj.css(attr);
        var num = parseFloat(ww); 
        speed = num > target ? -Math.abs(speed) : Math.abs(speed);
        obj.timer = setInterval(function (){
            num += speed;
            if( speed > 0 && num >= target || speed < 0 && num <= target  ){
                num = target;
                clearInterval(obj.timer);
                obj.timer = null;
                var mm=num+"px";
                obj.css(attr,mm);
                (typeof callBack === "function") && callBack();

            }else{
                var mm=num+"px";
                 obj.css(attr,mm)
            }
        },30)   
  }
    $(document).ready(function(){
        var t=0;
        var T_wen=$(".topUI");
        $(".next").click(function(){    
            var w=$(".Topdiv").innerHeight();
            var s=$(".topUI").innerHeight();    
            var mm=Math.floor(s/w)-1;
            if(t>=mm){
                return;
            }
            
            t++;
        doMove(T_wen,"top",10, -t*w);
    })
    $(".top").click(function(){
        
        var w=$(".Topdiv").innerHeight();
        var s=$(".topUI").innerHeight();
        if(t<=0){
            t=0;
            return;
        }
        t--;
        doMove( T_wen,"top",10, -t*w);
    })
    
    // 品牌商品上下翻页
    var m=0;
    var zc_wen=$(".ml");
        
    $(".itemList-next").click(function(){
        var w=$(".paipaisy_div_1").innerHeight();
        var s=$(".ml").innerHeight();   
        var mm=Math.floor(s/w)-1;
        if(m>=mm){
            return;
        }
        m++;
        doMove(zc_wen,"top",30, -m*w);
    })
    $(".itemList-prev").click(function(){
        var w=$(".paipaisy_div_1").innerHeight();
        var s=$(".ml").innerHeight();
        if(m<=0){
            m=0;
            return;
        }
        m--;
        doMove(zc_wen,"top",30, -m*w);
    })
    
   });
   