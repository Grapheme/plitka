var media = ['desktop', 'mobile'];
	media['desktop'] = [1460, 1040, 860];
	media['mobile'] = [680, 325];

var is_mobile = function() {
	return ($(window).width() <= media['mobile'][0]);
};

var mobile_menu = (function(){
	var open = false;
	var out = false;
	var header = $('.main-header');

	var init = function() {
		$(document).on('click', '.menu-icon', function(){
			if(open) {
				header.removeClass('open');
				out = setTimeout(function(){
					header.css({ 'overflow': 'hidden' });
				}, 500);
				open = false;
			} else {
				clearTimeout(out);
				setTimeout(function(){
					header.addClass('open');
				}, 1)
				header.css({ 'overflow': 'visible' });
				open = true;
			}
			return false;
		});
	};

	init();
})();

var scroll_menu = (function(){
	var header, orig_nav, orig_pos, header_height,
		header = $('.main-header');

	
	if(!is_mobile()) {
		setVars();
		setNav();
	}

	$(window).on('scroll', function(){
		if(!is_mobile()) {
			setNav();
		}
	});

	$(window).on('resize', function(){
		/*navReset();
		if(!is_mobile()) {
			setVars();
			setNav();
		}*/
		if(is_mobile()) {
			navReset();
		} else {
			setVars();
			if($(window).scrollTop() >= orig_nav) {
				setNav();
			}
		}
	});

	function setVars() {
		orig_nav = header.find('.head-nav').position().top;
		orig_pos = header.css('position');
		header_height = header.outerHeight();
	}

	function setMin() {
		setTimeout(function(){
				header.css({
				'position': 'fixed',
				'top': -orig_nav
			});
		}, 1);
		header.addClass('min-header');
	}

	function setBig() {
		setTimeout(function(){
			header.css({
				'position': 'absolute',
				'top': 0
			});
		}, 1);
		header.removeClass('min-header');
	}

	function setNav() {
		if($(window).scrollTop() >= orig_nav) {
			setMin();
		} else {
			setBig();
		}
	}

	function navReset() {
		/*if(is_mobile()) {
			header.css({
				'position': 'fixed',
				'top': 0
			});
		} else {
			setBig();
		}*/
		header.removeAttr('style');
		header.removeClass('min-header');
	}

	return {setMin: setMin, setBig: setBig};

})();

$.fn.main_slider = function(slide) {
	var direction = false;
	var down_timeout = false;
	var up_timeout = false;
	var active__frame = slide.eq(0);
	var allow = true;
	var length = slide.length - 1;

	if((!Modernizr.hasEvent('mousewheel') && !Modernizr.hasEvent('DOMMouseScroll')) || is_mobile()) {
		$(this).on('click', '.slide-link', function(){
			var index = $(this).parent().index();
			if(index != length) {
				$('html, body').animate({
					scrollTop: slide.eq(index+1).offset().top
				}, 250);
			} else {
				$('html, body').animate({
					scrollTop: 0
				}, 250);
			}
			return false;
		});
		return;
	}

	function init() {
		$('html').css('overflow', 'hidden');
		$('.main-header').addClass('animate');
		active__frame.addClass('active');
		slide.addClass('animate');
	}

	function setActive() {
		active__frame.addClass('active').siblings().removeClass('active');
	}

	function setTransform(limit, id) {
		if(active__frame.index() == limit) return;

		var perc = -1 * id * 100;
		$('.index-page').attr('style', '-webkit-transform: translateY(' + perc + '%); transform: translateY(' + perc + '%);');
		active__frame = slide.eq(id);
		setActive();
		if(active__frame.index() == 0) {
			scroll_menu.setBig();
		} else {
			scroll_menu.setMin();
		}
	}

	function down() {
		setTransform(length, active__frame.index() + 1);
	}

	function up() {
		setTransform(0, active__frame.index() - 1);
	}

	function goto(clear_time, act_time, func, condition) {
		allow = false;
		clearTimeout(clear_time);
		act_time = setTimeout(function(){
			if(condition) {
				func();
				setTimeout(function(){
					allow = true;
				}, 450);
			}
		}, 50);
	}

	$(this).on('mousewheel DOMMouseScroll', function(event){
		if (event.type == 'mousewheel') {
			delta = event.originalEvent.wheelDelta;
		} else

		if (event.type == 'DOMMouseScroll') {
			delta = -1 * event.originalEvent.detail;
		}

		if(!allow || Math.abs(delta) < 50) return;

		if(delta < 0) {
			goto(up_timeout, down_timeout, down, (delta < 0));
		} else if(delta > 0) {
			goto(down_timeout, up_timeout, up, (delta > 0));
		}
		return false;
	});

	$(this).on('click', '.slide-link', function(){
		if($(this).parent().index() != length) {
			down();
		} else {
			setTransform(0, 0);
		}
		return false;
	});

	$(document).on('keydown', function(e){
		var event = window.event ? window.event : e;
		var key = event.keyCode;
	    if(key == 40) {
	       down();
	    }
	    if(key == 38) {
	       up();
	    }
	});

	init();
};

$.fn.custom_fotorama = function(height, example) {

	height = typeof height !== 'undefined' ? height : false;
	example = typeof example !== 'undefined' ? example : false;

	var settings = {
		'nav': false,
		'arrows': false,
		'loop': true,
		'fit': 'cover',
		'width': '100%',
		'maxheight': height
	};

	$(this).fotorama(settings);

	var fotorama__nav = $(this).parent().parent().find('.fotorama-nav');
	var $fotoramaDiv =  $(this).fotorama(settings);
    var fotorama = $fotoramaDiv.data('fotorama');

    function setExample() {
    	$('.examples-tabs .example')
    	.eq(fotorama.activeIndex).addClass('active')
    	.siblings().removeClass('active');
    }

    fotorama__nav.find('span').eq(0).text(fotorama.activeIndex + 1);
    fotorama__nav.find('span').eq(1).text(fotorama.size);
    if(example) setExample();

    $(document).on('click', '.slider-container .prev', function(){
    	fotorama.show('<');
    	return false;
    });

    $(document).on('click', '.slider-container .next', function(){
    	fotorama.show('>');
    	return false;
    });

    $(this).on(
	  'fotorama:show',
	  function () {
	    fotorama__nav.find('span').eq(0).text(fotorama.activeIndex + 1);
	    if(example) setExample();
	  }
	);
};

$.fn.tabs = function() {
	var block = $(this).attr('data-tabs');
	var tab_links = $(this).find('li');
	var tab_parent = $(this);
	var block_parent = $('.js-parent[data-tabs="' + block + '"]');
	var blocks = block_parent.find('.js-tab');

	$.fn.linkActive = function() {
		$(this).addClass('active')
		.siblings().removeClass('active');
	};

	$.fn.tabActive = function() {
		$(this).show()
		.siblings().hide();
	};

	function go(id) {
		tab_parent.find('[data-tab="' + id + '"]').linkActive();
		block_parent.find('[data-block="' + id + '"]').tabActive();
	}

	function init() {
		blocks.first().tabActive();
		tab_links.first().linkActive();
	}

	init();

	$(document).on('click', '.js-tabs li',function(){
		var data = $(this).attr('data-tab');
		go(data);
		return false;
	});
};

