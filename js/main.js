jQuery(document).ready(function($) 
{
	var options={};
	var i = 0;
	var slide = new Array();
	for(i=0; i<config.backgrounds.length; i++)
	{
		slide[i] = {
			image: config.backgrounds[i],
			title: config.background_title[i]
		};
	}
	var page = new Object();
	for(i=0; i<config.link.length; i++)
		page[config["link"][i]] = {tab:config["direction"][i], className: config["icon"][i], title: config["title"][i]};
	
	$('#nostalgia').nostalgia(options,page,slide);
	//audio
	if(typeof(config.tracks)!='undefined' && config.tracks!=null && config.tracks.length)
	{
		i=0;
		$("#jPlayer").jPlayer({
			ready: function() {
				$(this).jPlayer("setMedia", {
					mp3: config.tracks[i]
				});
				if(parseInt(config.music_autoplay))
				{
					$(this).jPlayer("play");
					var click = document.ontouchstart === undefined ? 'click' : 'touchstart';
					var kickoff = function () {
						$("#jPlayer").jPlayer("play");
						document.documentElement.removeEventListener(click, kickoff, true);
					};
					document.documentElement.addEventListener(click, kickoff, true);
				}
			},
			ended: function(){
				i++;
				if(i>=config.tracks.length)
				{
					if(parseInt(config.music_loop))
						i=0;
					else
						return;
				}
				$(this).jPlayer("setMedia", {
					mp3: config.tracks[i]
				}).jPlayer("play");
			},
			swfPath: config.swfPath,
			loop: parseInt(config.music_loop)
		});
		$(".jPlayerControl").click(function(event){
			event.preventDefault();
			if($(this).hasClass("inactive") || $(this).hasClass("muted"))
			{
				$("#jPlayer").jPlayer("play");
				$("#jPlayerPortfolio").jPlayer("stop");
				$(".jPlayerControl").removeClass("inactive muted");
				$(".audio-item").removeClass("playing");
			}
			else
			{
				$("#jPlayer").jPlayer("pause");
				$(".jPlayerControl").addClass("muted");
			}
		});
	}
	$("#jPlayerPortfolio").jPlayer({
		ready: function() {
			var click = document.ontouchstart === undefined ? 'click' : 'touchstart';
			var kickoff = function () {
				$("#jPlayerPortfolio").jPlayer("play");
				document.documentElement.removeEventListener(click, kickoff, true);
			};
			document.documentElement.addEventListener(click, kickoff, true);
		},
		swfPath: config.swfPath,
		loop: parseInt(config.music_loop)
	});
	$(".audio-item").live("click", function(event){
		event.preventDefault();
		var self = $(this);
		$("#jPlayer").jPlayer("stop");
		$(".jPlayerControl").addClass("inactive").removeClass("muted");
		$("#jPlayerPortfolio").jPlayer("stop");
		var addClass = false;
		if(!$(this).hasClass("playing"))
		{
			$("#jPlayerPortfolio").jPlayer("setMedia", {
				mp3: self.attr("href")
			});
			$("#jPlayerPortfolio").jPlayer("play");
			addClass = true;
		}
		$(".audio-item").removeClass("playing");
		if(addClass)
			$(this).addClass("playing");
	});
	//widget control
	$(".widgetControl").click(function(event){
		event.preventDefault();
		$(this).toggleClass("inactive");
		$(".sidebar-home").fadeToggle();
		$(this).removeClass("autoClosed");
	});
	//home sidebar
	//twitter
	$(".widget_twitter ul").bxSlider(
	{
		auto:true,
		pause:5000,
		nextText:null,
		prevText:null,
		mode:'vertical',
		displaySlideQty:1,
		wrapperClass:'bx-wrapper bx-wrapper-twitter' 
	});
	//latest portfolio
	$('.fancybox-image.fancybox-latest-portfolio').fancybox({
		'titlePosition': 'inside'
	});
	/**************************************************************************/
	$('.fancybox-video.fancybox-latest-portfolio').bind('click',function() 
	{
		$("#jPlayer").jPlayer("stop");
		$(".jPlayerControl").addClass("inactive").removeClass("muted");
		$("#jPlayerPortfolio").jPlayer("stop");
		$(".audio-item").removeClass("playing");
		$.fancybox(
		{
			//'padding':0,
			'autoScale':false,
			'titlePosition': 'inside',
			'title': this.title,
			'transitionIn':'none',
			'transitionOut':'none',
			'width':(this.href.indexOf("vimeo")!=-1 ? 600 : 680),
			'height':(this.href.indexOf("vimeo")!=-1 ? 338 : 495),
			'href':(this.href.indexOf("vimeo")!=-1 ? this.href : this.href.replace(new RegExp("watch\\?v=", "i"), 'embed/')),
			'type':'iframe',
			'swf':
			{
				'wmode':'transparent',
				'allowfullscreen':'true'
			}
		});

		return false;
	});
	/**************************************************************************/
	$(".fancybox-iframe.fancybox-latest-portfolio").fancybox({
		'width' : '75%',
		'height' : '75%',
		'autoScale' : false,
		'titleShow': false,
		'type' : 'iframe'
	});
	/**************************************************************************/
	$('a.fancybox-image.fancybox-latest-portfolio img,a.fancybox-video.fancybox-latest-portfolio img, .audio-item.fancybox-latest-portfolio img').each(function() 
	{
		$(this).attr('src',$(this).attr('src') + '?i='+getRandom(1,100000));
		$(this).bind('load',function() { $(this).parent('a').css('background-image','none'); $(this).fadeIn(1000); });
	});
});