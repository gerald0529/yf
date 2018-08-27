document.createElement('header');
document.createElement('nav');
document.createElement('section');
document.createElement('article');
document.createElement('aside');
document.createElement('footer');



window.onload = function () {
	setTimeout(function () {
		$(".loading").fadeOut();
	}, 1000)

}
$(document).ready(function () {
	var off = true;
	$(".voice").click(function () {
		if (off) {
			$(".written").css("margin-top", "-75px");
			$(".chatinterface-ft").addClass("dynamic");
			$(".voiceimg").addClass("hide");
			$(".voice .kbimg").removeClass("hide");

			$(".voice .voiceimg").removeClass(" show");
			$(".voice .voiceimg").addClass("hide");
			off = false;
		} else {
			$(".written").css("margin-top", "0px");
			$(".voiceimg").removeClass("hide");
			$(".kbimg").addClass("hide");
			$(".chatinterface-ft").addClass("dynamic");
			$(".textms").focus();
			off = true;
		}
	})

	function stopPropagation(e) {
		if (e.stopPropagation) {
			e.stopPropagation();
		} else {
			e.cancelBubble = true;
		}
	}
	var a = true;
	var b = true;
	$(".mores").click(function (e) {
		if (a) {
			b = a;
			$(".chatinterface-ft").removeClass("dynamic");
			$(".manyemoticon").removeClass("show");
			$(".manyemoticon").addClass("hide");
			$(".More").removeClass("hide");
			$(".More").addClass("show");
			$(".bqimg").addClass("show");
			$(".text .kbimg ").addClass("hide");
			$(".text .kbimg ").removeClass("show");
			$(".written").css("margin-top", "0px");
			$(".voice .kbimg").removeClass("show");
			$(".voice .kbimg").addClass("hide");
			$(".voice .voiceimg").removeClass("hide");
			$(".voice .voiceimg").addClass("show");
			a = false;
		} else {
			$(".chatinterface-ft").addClass("dynamic");
			$(".More").removeClass("show");
			$(".More").removeClass("hide");
			$(".manyemoticon").removeClass("hide");
			$(".textms").focus();
			a = true;
		}
		e.stopPropagation();
	});
	$(".expression").click(function (e) {
		if (b) {
			a = b;
			$(".chatinterface-ft").removeClass("dynamic");
			$(".manyemoticon").addClass("show");
			$(".manyemoticon").removeClass("hide");
			$(".More").addClass("hide");
			$(".More").removeClass("show");
			$(".bqimg").addClass("hide");
			$(".text .kbimg ").addClass("show");
			$(".bqimg").removeClass("show");
			b = false;
		} else {
			$(".chatinterface-ft").addClass("dynamic");
			$(".manyemoticon").removeClass("show");
			$(".manyemoticon").removeClass("hide");
			$(".More").removeClass("hide");
			$(".bqimg").removeClass("hide");
			$(".bqimg").addClass("show");
			$(".text .kbimg ").addClass("hide");
			$(".text .kbimg ").removeClass("show");
			$(".textms").focus();
			b = true;
		}
		e.stopPropagation();
	});

	$('#emoji_div').click(function(e){
		$('.Sendout').show();
		e.stopPropagation();
	})
	$(".chatinterface-main").click(function () {
		$(".chatinterface-ft").addClass("dynamic");
		$(".More").removeClass("show");
		$(".More").removeClass("hide");
		$(".manyemoticon").removeClass("show");
		$(".manyemoticon").removeClass("hide");
		$(".kbimg").removeClass("show");
		$(".bqimg").addClass("show");
	})
	$(document).bind('click', function () {
		$(".public-hd").find(".setUp").css("display", "none");
	});

	var s = true;
	$(".closes").click(function () {
		if (s) {
			document.documentElement.style.overflow = 'hidden';
			$(".searchContact").addClass("show");
			$(".searchContact").addClass("dynamic02");
			s = false;

		} else {
			$(".searchContact").removeClass("show");
			$(".searchContact").removeClass("dynamic02");
			document.documentElement.style.overflow = 'auto';
			s = true;
		}
	})
	$(".index-ft #news").click(function () {
		$(".lately").addClass("show");
		$(".contacts").removeClass("show");
		$(".setUps").removeClass("show");
		//		$("body").css("background", "#fff");
		document.documentElement.style.overflow = 'auto';
	})
	$(".index-ft #Contacts").click(function () {
		$(".contacts").addClass("show");
		$(".lately").removeClass("show");
		$(".setUps").removeClass("show");
		$(".lately").css("display", "none");
		document.documentElement.style.overflow = 'auto';
	})
	$(".index-ft #setUps").click(function () {
		$(".setUps").addClass("show");
		$(".contacts").removeClass("show");
		$(".lately").removeClass("show");
		$(".lately").css("display", "none");
		//		$("body").css("background", "#f7f7f7");
		document.documentElement.style.overflow = 'auto';

		var timer = setInterval(function () {
			window.scrollBy(0, -50);
			if (document.body.scrollTop == 0) {
				clearInterval(timer);
			};
		}, 0);


	})
	$(".movea").click(function (e) {
		$(".public-hd").find(".setUp").stop().fadeToggle(1000);
		e.stopPropagation();
	})

	$(".QRcode").click(function () {
		$(".mask").addClass("show");
		$(".qrcodes").addClass("show");
	})
	$(".mask").click(function () {
		$(".mask").removeClass("show");
		$(".qrcodes").removeClass("show");
	})
	$(".qrcodes").click(function () {
		$(".mask").removeClass("show");
		$(".qrcodes").removeClass("show");
	})

	$(".textms").click(function () {
		$(".chatinterface-ft").addClass("dynamic");
		$(".kbimg ").removeClass("show");
		$(".bqimg ").addClass("show");
	})
	$(".Sendout").click(function () {
		$(".Sendout").css("display", "none");
	})

})

function offtext() {
	var text = document.getElementById("im_send_content").value;
	if (text !== "") {
		$(".Sendout").css("display", "block");
	} else {
		$(".Sendout").css("display", "none");
	}
}

$(document).on('')












