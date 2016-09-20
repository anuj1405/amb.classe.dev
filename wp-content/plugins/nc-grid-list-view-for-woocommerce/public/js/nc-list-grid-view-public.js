//list and toggle view script

jQuery(document).ready(function($) {
	
	var _default=$('#nc-grid-list-view-assets').attr('data-default-layout');
	if(_default=='list')
	{
	$("a#list-icon").css("opacity",0.5);
    $("a#grid-icon").css("opacity",1);
	$('ul.products').addClass(_default);
	}
    
   $('#nc-list-grid-icons a').click(function(){
	var att=$(this).attr("data-id");
	var prod_wrap=$("ul.products");
	if(att=='grid' && !prod_wrap.hasClass("grid"))
	{
		$("a#grid-icon").css("opacity",0.5);
		$("a#list-icon").css("opacity",1);
		$('ul.products').animate({opacity:0.5},function(){
    	$('ul.products').removeClass('list');
   		$('ul.products').addClass('grid');
    	$('ul.products').stop().animate({opacity:1});
  	 });
	
	}
	else if(att=='list' && !prod_wrap.hasClass("list")){
		
		 $("a#list-icon").css("opacity",0.5);
		  $("a#grid-icon").css("opacity",1);
		 $('ul.products').animate({opacity:0.5},function(){
     	 $('ul.products').removeClass('grid');
    	 $('ul.products').addClass('list');
    	 $('ul.products').stop().animate({opacity:1});
  	 });
		}
		
		});

})
