function packTheContactForm(e){var t=100/70,a=e.width()/e.height(),i=e.outerWidth()+10,s=parseInt(e.height()*a/t),n=jQuery(".contact-blocks-env");n.height(n.height()),TweenLite.to(e,.5,{css:{height:s},onComplete:function(){resizeEmailIcon(i,s,e);var t=new TimelineLite;t.append(TweenLite.to(e,.2,{css:{scale:.8},delay:1.5})),t.append(TweenLite.to(e,.5,{css:{left:200,autoAlpha:0},ease:Back.easeIn,onComplete:function(){TweenLite.to(e,.5,{css:{height:0}}),setTimeout(function(){jQuery(".contact-form-block .success-message").slideDown("normal")},500)}}))}})}function resizeEmailIcon(e,t,a){var i=jQuery(".mail-sent"),s=i.find(".mail-left"),n=i.find(".mail-right"),o=i.find(".mail-top"),r=i.find(".mail-bottom"),l=5;i.css({width:e,height:t}),i.fadeIn(300,function(){i.addClass("visible")});var c=t/2-l,d=e/2-l,u=e/2;s.css("border-width",c+"px 0 "+c+"px "+c+"px"),n.css("border-width",c+"px "+c+"px "+c+"px 0"),o.css("border-width",d+"px "+d+"px 0"+d+"px"),r.css("border-width","0 "+u+"px "+u+"px "+u+"px")}function setupCollapsedSidebarMenu(){var $=jQuery,e=public_vars.$sidebarMenu.find("> .nav"),t=e.find("> li:has(ul)"),a=.35,i=Quad.easeInOut;odd_len=100,even_len=200,opacity_class="opacity-hidden",calculateMenuHeights(t),e.find("li:has(ul):not(.current-menu-ancestor)").each(function(e,t){var s=$(t),n=s.children("ul"),o=s.data("height"),r=n.find("> li:odd"),l=n.find("> li:even"),c=0,d=0;n.find("> li").addClass(opacity_class),s.hoverIntent({over:function(){window.clearTimeout(c),window.clearTimeout(d),c=setTimeout(function(){r.removeClass(opacity_class)},1e3*a-odd_len),d=setTimeout(function(){l.removeClass(opacity_class)},1e3*a-even_len),TweenLite.to(n,a,{css:{height:o},ease:i,onComplete:function(){n.css({height:"auto"})}})},out:function(){window.clearTimeout(c),window.clearTimeout(d),c=setTimeout(function(){r.addClass(opacity_class)},odd_len),d=setTimeout(function(){l.addClass(opacity_class)},even_len),TweenLite.to(n,a,{css:{height:0},delay:Math.max(odd_len,even_len)/1e3+a/2,ease:i})},timeout:100})}),e.find(".current_page_ancestor:has(ul)").each(function(e,t){var a=$(t),i=a.children("ul");i.find("li").removeClass(opacity_class),i.css({height:"auto"})})}function calculateMenuHeights(e){var $=jQuery;e.each(function(e,t){var a=$(t),i=a.children("ul");i.css({display:"block",height:"auto"});var s=i.outerHeight(!0);a.data("height",s),i.find("> li:has(ul)").length&&calculateMenuHeights(i.find("> li"))}),e.find("> ul").removeAttr("style").css({display:"block",height:0})}function validateEmail(e){var t=/^[a-zA-Z0-9._]+[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z\.]{2,}$/;return t.test(e)}function updateCartItemsNumber(e){var t=public_vars.$cartRibbon.find(".bucket"),a=public_vars.$cartRibbon.find(".number").add(public_vars.$mobileMenu.find(".cart-items span")),i=parseInt(public_vars.$cartRibbon.css("padding-top"),10);TweenLite.to(public_vars.$cartRibbon,.2,{css:{paddingTop:1.5*i}}),TweenLite.to(public_vars.$cartRibbon,.1,{css:{paddingTop:i},delay:.2}),TweenLite.to(a,.2,{css:{scale:.4},onComplete:function(){a.html(e),TweenLite.to(a,.2,{css:{scale:1}});var i=.2;TweenLite.to(t,i,{css:{transform:"rotate(5deg)"}}),TweenLite.to(t,i,{css:{transform:"rotate(-5deg)"},delay:1*i}),TweenLite.to(t,i,{css:{transform:"rotate(0)"},delay:2*i})}})}function updateHeaderCart(e,t){var a=public_vars.$cartItems.data("owlCarousel");"object"==typeof a?(a.destroy(),public_vars.$cartItems.hide(),TweenLite.to(public_vars.$cartItems,.1,{css:{autoAlpha:0},onComplete:function(){public_vars.$cartItems.html(t),public_vars.$cartItems.show(),public_vars.$cartItems.owlCarousel({items:4,navigation:!0,pagination:!1}),TweenLite.to(public_vars.$cartItems,.2,{css:{autoAlpha:1}})}})):jQuery.isFunction(jQuery.fn.owlCarousel)&&(public_vars.$cartItems.html(t),public_vars.$cartItems.owlCarousel({items:4,navigation:!0,pagination:!1})),TweenLite.to(public_vars.$cartSubTotal,.3,{css:{autoAlpha:0},onComplete:function(){public_vars.$cartSubTotal.html(e),TweenLite.to(public_vars.$cartSubTotal,.3,{css:{autoAlpha:1}})}})}function updateShippingMethods(e){if("undefined"!=typeof wc_cart_params){var t={action:"laborator_update_shipping_method",security:wc_cart_params.update_shipping_method_nonce,shipping_method:[e]};jQuery(".cart-totals").addClass("is-loading"),jQuery.post(ajaxurl,t,function(e){jQuery(".cart-totals").removeClass("is-loading"),jQuery(".cart-totals .subtotal .value").html(e.subtotal),jQuery(".cart-totals .total .value").html(e.total),jQuery(".cart-totals .tax-rate .value").html(e.vat_total)},"json")}else if("undefined"!=typeof wc_checkout_params){var $=jQuery,a=$("#order_review input[name=payment_method]:checked").val(),i=$("#billing_country").val(),s=$("#billing_state").val(),n=$("input#billing_postcode").val(),o=$("#billing_city").val(),r=$("input#billing_address_1").val(),l=$("input#billing_address_2").val(),c,d,u,h,p,v;$("#ship-to-different-address input").is(":checked")||0===$("#ship-to-different-address input").size()?(c=$("#shipping_country").val(),d=$("#shipping_state").val(),u=$("input#shipping_postcode").val(),h=$("#shipping_city").val(),p=$("input#shipping_address_1").val(),v=$("input#shipping_address_2").val()):(c=i,d=s,u=n,h=o,p=r,v=l);var t={action:"laborator_update_order_review",security:wc_checkout_params.update_order_review_nonce,shipping_method:[e],payment_method:a,country:i,state:s,postcode:n,city:o,address:r,address_2:l,s_country:c,s_state:d,s_postcode:u,s_city:h,s_address:p,s_address_2:v,post_data:$("form.checkout").serialize()};jQuery(".cart-totals").addClass("is-loading"),xhr=$.ajax({type:"POST",dataType:"json",url:wc_checkout_params.ajax_url,data:t,success:function(e){e&&(jQuery(".cart-totals").removeClass("is-loading"),jQuery(".cart-totals .cart-subtotal .value").html(e.subtotal),jQuery(".cart-totals .order-total .value").html(e.total),jQuery(".cart-totals .tax-rate .value").html(e.vat_total),$("body").trigger("updated_checkout"))}})}}function launchFullscreen(e){e.requestFullscreen?e.requestFullscreen():e.mozRequestFullScreen?e.mozRequestFullScreen():e.webkitRequestFullscreen?e.webkitRequestFullscreen():e.msRequestFullscreen&&e.msRequestFullscreen()}function exitFullscreen(){document.exitFullscreen?document.exitFullscreen():document.mozCancelFullScreen?document.mozCancelFullScreen():document.webkitExitFullscreen&&document.webkitExitFullscreen()}function forceFullWidth(e){e.css({marginLeft:"",width:""});var t=e.offset().left;e.css({width:jQuery(document).width(),marginLeft:-t}),jQuery(window).on("lab.resize",function(){forceFullWidth(e)})}function setupStickMenu(){var $=jQuery,e=public_vars.$body.hasClass("ht-1")?1:public_vars.$body.hasClass("ht-2")?2:3;if(!(jQuery(window).height()>public_vars.$body.outerHeight()-200||public_vars.$body.outerHeight()<1.2*jQuery(window).height())){if(2==e){var t=public_vars.$body.find(".top-menu"),a=public_vars.$body.find(".main-menu-top"),i=$("<div></div>"),s=public_vars.$body.hasClass("admin-bar")?32:0;if(!t.length)return;var n=scrollMonitor.create(t,-s);n.lock(),t.after(i);var o=public_vars.$stickyLogo.next().innerHeight();public_vars.$stickyLogo.css({display:"block"}).data("width",public_vars.$stickyLogo.innerWidth()).css({width:0,lineHeight:o+"px"}),public_vars.$stickyLogo.hasClass("image-logo")&&public_vars.$stickyLogo.data("width",0).find("img").css({maxHeight:o-30}),a.css({top:s}),n.exitViewport(function(){isxs()||(t.hide(),i.height(t.height()+a.outerHeight(!0)),a.addClass("sticky"),TweenLite.to(public_vars.$stickyLogo,.3,{css:{width:public_vars.$stickyLogo.data("width"),autoAlpha:1}}))}),n.enterViewport(function(){isxs()||(t.attr("style",""),i.height(0),a.removeClass("sticky"),TweenLite.to(public_vars.$stickyLogo,.3,{css:{width:0,autoAlpha:0}}))}),$(window).load(function(){if(public_vars.$stickyLogo.hasClass("image-logo"))public_vars.$stickyLogo.css({width:""}).data("width",public_vars.$stickyLogo.width()).css({width:0});else{var e=public_vars.$stickyLogo.data("width");public_vars.$stickyLogo.css({width:""}).data("width",public_vars.$stickyLogo.width()).css("width",0),a.hasClass("sticky")&&public_vars.$stickyLogo.css({width:e})}a.hasClass("sticky")&&TweenLite.to(public_vars.$stickyLogo,.3,{css:{width:public_vars.$stickyLogo.data("width"),autoAlpha:1}})})}if(3==e){var t=public_vars.$body.find(".top-menu"),r=t.find(".sec-nav-menu"),i=$('<div class="header-menu-spacer"></div>'),s=public_vars.$body.hasClass("admin-bar")?32:0;if(tm_out=0,t.length){var n=scrollMonitor.create(t,-s);n.lock(),t.after(i);var o=public_vars.$stickyLogo.next().innerHeight();return n.exitViewport(function(){isxs()||(i.height(t.outerHeight()+20),t.addClass("sticky-header").removeClass("is-hidden show-header"),window.clearTimeout(tm_out),tm_out=setTimeout(function(){t.addClass("visible"),r.addClass("hidden")},100))}),void n.fullyEnterViewport(function(e){!isxs()&&e&&(t.addClass("not-visible"),setTimeout(function(){i.height(0),t.removeClass("sticky-header visible").addClass("is-hidden"),r.removeClass("hidden"),r.next().removeClass("hidden"),tm_out&&window.clearTimeout(tm_out),tm_out=setTimeout(function(){t.addClass("show-header")},50),setTimeout(function(){t.addClass("not-visible-finished")},150)},150))})}}if(3==e){t=public_vars.$body.find(".top-menu-centered");var l=t.find(".navs");if(t.length&&l.length){l.before(i);var c=scrollMonitor.create(l,{top:s});c.lock(),c.partiallyExitViewport(function(){var e=l.offset().left,t=l.width();l.addClass("is-fixed").css({width:"auto"}),i.height(l.outerHeight())}),c.fullyEnterViewport(function(){l.removeClass("is-fixed"),i.removeAttr("style")})}}}}var public_vars=public_vars||{};!function($,e,t){"use strict";$(document).ready(function(){public_vars.$body=$("body"),public_vars.$mainSidebar=$(".main-sidebar"),public_vars.$sidebarMenu=public_vars.$mainSidebar.find(".sidebar-menu"),public_vars.$cartRibbon=public_vars.$body.find(".cart-ribbon .cart_content"),public_vars.$headerCart=public_vars.$body.find(".header-cart"),public_vars.$cartSubTotal=public_vars.$headerCart.find(".cart-sub-total .amount"),public_vars.$cartItems=public_vars.$headerCart.find(".cart-items"),public_vars.$topMainMenu=public_vars.$body.find(".main-menu-env li:has(> ul)"),public_vars.$mobileMenu=public_vars.$body.find(".mobile-menu"),public_vars.$stickyLogo=public_vars.$body.find(".logo-sticky"),$.isFunction($.fn.perfectScrollbar)&&public_vars.$mainSidebar.perfectScrollbar(),public_vars.$sidebarMenu.hasClass("collapsed-subs")&&setupCollapsedSidebarMenu(),$.extend(public_vars,{hoverIndex:100,mainTopMenuFall:15,mainTopMenuDuration:.3}),0==public_vars.$body.find(".main-menu-env .megaMenuContainer").length&&public_vars.$topMainMenu.each(function(e,t){var a=$(t),i=a.find("> ul"),s="UL"==a.parent().parent().parent()[0].tagName;s?TweenLite.set(i,{css:{x:-public_vars.mainTopMenuFall,y:0}}):TweenLite.set(i,{css:{y:public_vars.mainTopMenuFall}}),a.hoverIntent({over:function(){public_vars.hoverIndex++,a.css({overflow:"visible"}),i.css({zIndex:public_vars.hoverIndex}),s?TweenLite.to(i,public_vars.mainTopMenuDuration,{css:{x:0,autoAlpha:1}}):TweenLite.to(i,public_vars.mainTopMenuDuration,{css:{y:0,autoAlpha:1}})},out:function(){i.css({zIndex:public_vars.hoverIndex}),s?TweenLite.to(i,public_vars.mainTopMenuDuration,{css:{x:-public_vars.mainTopMenuFall,autoAlpha:0},onComplete:function(){a.css({overflow:"hidden"})}}):TweenLite.to(i,public_vars.mainTopMenuDuration,{css:{y:public_vars.mainTopMenuFall,autoAlpha:0},onComplete:function(){a.css({overflow:"hidden"})}})},timeout:200})}),$(".autogrow").autoGrow();var t=[],a=function(){if(t.length){var e=t.shift(),i=new Image;i.src=e.src,i.onload=function(){e.$el.hasClass("lab-lazy-load")&&e.$el.attr("src",e.src).removeClass("lab-lazy-load"),a()}}};if($("img:not(.lab-lazy-load):visible").each(function(e,a){var i=$(a),s=i.attr("src");t.push({i:e,src:s,$el:i})}),$("img.lab-lazy-load").each(function(e,a){var i=$(a),s=i.data("src");t.push({i:e,src:s,$el:i})}),$(e).load(a),$.isFunction($.fn.nivoLightbox)&&$(".nivo a, .post_details .post-content a").nivoLightbox({effect:"fade",theme:"default"}),$.isFunction($.fn.cycle)){var i=$(".post-imgs-slider");$.fn.cycle.log=function(){},imagesLoaded(i.get(0),function(){i.find(".loading").remove(),i.find("> a.hidden").removeClass("hidden"),i.cycle({slides:"> a",prev:i.find(".pager .prev"),next:i.find(".pager .next"),log:function(){}})})}var s=$(".contact-form");if(s.length&&s.find("form").length){var n=s.find("form"),o=s.find("h4");n.submit(function(e){e.preventDefault();var t=n.serializeArray(),a=n.find('[data-required="1"]');if(a.length){var i=a.serializeArray(),o=[];for(var r in i)o.push(i[r].name)}for(var r in t.reverse()){var l=t[r],c=n.find('[name="'+l.name+'"]');-1!=$.inArray(l.name,o)&&(""==$.trim(c.val())?c.addClass("has-errors").focus():c.removeClass("has-errors")),"email"==l.name&&c.val().length&&(validateEmail(c.val())?c.removeClass("has-errors"):c.addClass("has-errors").focus())}n.find(".has-errors").length||s.hasClass("is-loading")||s.data("is-done")||(t.push({name:"action",value:"cf_process"}),t.push({name:"verify",value:n.data("check")}),t.push({name:"id",value:n.data("id")}),s.addClass("is-loading"),$.post(ajaxurl,t,function(e){if(e.success){var t=s.find(".success-message");t.show(),s.removeClass("is-loading"),s.data("is-done",1),s.find("[name]").fadeTo(200,.6).attr("readonly",!0),packTheContactForm(s)}else alert("An error occured your message cannot be send!")},"json"))})}$.isFunction($.fn.tooltip)&&$('a[data-toggle="tooltip"], span[data-toggle="tooltip"]').tooltip({}),$(".product.has-gallery").each(function(e,t){var a=$(t),i=a.find(".image.full-gallery .thumb img");if(i.length>1){a.find(".image").append('<a href="#" class="thumb-prev">Prev</a><a href="#" class="thumb-next">Next</a>');var s=a.find(".thumb-prev, .thumb-next");s.on("click",function(e){e.preventDefault();var t=$(this).hasClass("thumb-prev")?-1:1,a=i.filter(":not(.hidden-slowly)"),s=a.next();1==t?0==s.length&&(s=i.first()):(s=a.prev(),0==s.length&&(s=i.last())),s.addClass("enter-in notrans "+(-1==t?"ei-left":"")).removeClass("hidden hidden-slowly hs-left hs-right"),a.addClass("hidden-slowly "+(-1==t?"hs-left":"")),setTimeout(function(){s.removeClass("enter-in notrans ei-left")},0)})}}),$(".product .add-to-cart[data-id]").each(function(e,t){var a=$(t),i=a.closest(".product");a.on("click",function(e){if(e.preventDefault(),!a.hasClass("added")){i.addClass("is-loading"),a.tooltip("hide");var t={action:"lab_add_to_cart",product_id:a.data("id")};$.post(ajaxurl,t,function(e){if(i.removeClass("is-loading"),e.success)a.addClass("added").tooltip("destroy"),updateCartItemsNumber(e.cart_items),updateHeaderCart(e.cart_subtotal,e.cart_html),setTimeout(function(){a.removeClass("added"),a.tooltip()},1500),$(document).trigger("added_to_cart",[a,e.cart_html]),$(".widget_shopping_cart").show(),$(".widget_shopping_cart_content").html(e.cart_html_frag);else{var t=i.find(".error-container");i.addClass("has-errors"),t.html(""),$.each(e.error_msg,function(e,a){t.html($("<span>").html(a))}),setTimeout(function(){i.removeClass("has-errors")},2e3)}},"json")}})}),public_vars.$cartRibbon.closest(".cart-ribbon").on("click",function(t){t.preventDefault(),public_vars.$body.hasClass("header-cart-open")?public_vars.$headerCart.slideUp("normal",function(){public_vars.$body.removeClass("header-cart-open")}):(public_vars.$headerCart.slideDown(function(){$(e).on("click",r)}),public_vars.$body.addClass("header-cart-open"))});var r=function(t){public_vars.$headerCart.is(":visible")&&($(t.target).closest(".header-cart").length||($(e).off("click",r),public_vars.$headerCart.slideUp("normal",function(){public_vars.$body.removeClass("header-cart-open")})))};$(".cart-totals #shipping_method input").on("change",function(){var e=$(this).val();updateShippingMethods(e)}),$(".cart-totals select.shipping_method").on("change",function(){var e=$(this).val();updateShippingMethods(e)}),$("body").on("updated_checkout",function(){$.isFunction($.fn.iCheck)&&$('input[type="checkbox"], input[type="radio"]').each(function(e,t){!$(t).data("iCheck")})});var l=$(".myaccount-env .myaccount-tabs li > a");l.each(function(t,a){var i=$(a);if(i.attr("href").match(/^\#/)){var s=$(".myaccount-tab"+i.attr("href"));e.location.hash.toString()==i.attr("href")&&setTimeout(function(){s.length||(s=$(".myaccount-tab"+i.attr("href")));var e=$(".myaccount-tab").not(s);e.hide(),s.show(),l.parent().removeClass("active"),i.parent().addClass("active")},1),i.on("click",function(t){t.preventDefault(),s.length||(s=$(".myaccount-tab"+i.attr("href")));var a=$(".myaccount-tab").not(s);a.hide(),s.fadeIn(300),l.parent().removeClass("active"),i.parent().addClass("active");var n=$(e).scrollTop();e.location.hash=i.attr("href").replace("#",""),$(e).scrollTop(n)})}});var c=$(".product-single .variations select, select.oxy-list");c.each(function(e,t){var a=$(t);a.css({width:"100%"}).wrap($('<div class="select-wrapper" />'));var i=a.parent(),s=$('<span class="select-placeholder" />');i.prepend(s),i.prepend('<div class="select-arrow" />'),s.html(a.find("option:selected").html()),a.on("change",function(e){s.html(a.find("option:selected").html())})});var d=$("#laborator-rating");if(d.length){var u=$('<div class="rating" />');d.hide().after(u),$(d.find("option").get().reverse()).each(function(e,t){var a=$(t),i=5-e;a.attr("value").match(/[0-9]+/)&&u.prepend('<a href="#" data-rating="'+i+'" class="glyphicon glyphicon-star star-'+i+'"></a>')}),u.data("current-rating",0),u.on("click","a",function(e){e.preventDefault();var t=$(this),a=t.data("rating");d.find("option").attr("selected",!1),u.data("current-rating")==a?(u.attr("class","rating"),u.data("current-rating",0)):(u.attr("class","rating filled-"+a),u.data("current-rating",a),d.find('option[value="'+a+'"]').attr("selected",!0))}).on("mouseover","a",function(e){u.removeClass("hover-1 hover-2 hover-3 hover-4 hover-5").addClass("hover-"+$(this).data("rating"))}).on("mouseout","a",function(e){u.removeClass("hover-1 hover-2 hover-3 hover-4 hover-5")})}if("undefined"!=typeof CBPGridGallery){var h=$(".laborator-woocommerce .shop-grid");h.each(function(e,t){new CBPGridGallery(t)})}var p=$(".yith-add-to-wishlist"),v=$(null);$("body").on("click",".yith-add-to-wishlist",function(){v=$(this)}),$("body").on("added_to_wishlist",function(e,t){v.removeClass("is-loading"),v.parent().addClass("wishlisted")}),$(".yith-add-to-wishlist").each(function(e,t){$(t).on("click",function(e){e.preventDefault(),$(t).addClass("is-loading")})}),$(e).bind("cbpOpen",function(e,t,a){var i=$(a.currentItem),s=i.data("owlCarousel");s||i.find(".product-gallery").owlCarousel({items:1,navigation:!0,pagination:!1,singleItem:!0,slideSpeed:400,beforeInit:function(){var e=i.find(".product-gallery .hidden");e.length>0&&(e.closest(".product-gallery-env").addClass("has-gallery"),e.removeClass("hidden"))}})});var f=$(".main-menu-top .search-form, .top-menu-centered .search-form");if(f.length){var m=f.find(".search-input-env"),b=m.find("input"),_=m.val();f.on("click","a",function(e){return e.preventDefault(),b.val().length>0&&_!=m.val()?void f.submit():(b.val().length&&!m.is(":visible")&&_!=m.val()&&f.submit(),m.toggleClass("visible"),void(m.hasClass("visible")&&setTimeout(function(){b.focus()},100)))}),b.on("blur",function(){m.removeClass("visible")})}"undefined"!=typeof WOW&&setTimeout(function(){(new WOW).init()},500),$(".vc_separator.one-line-border, .vc_separator.double-bordered, .vc_separator.double-bordered-thin, .vc_separator.double-bordered-thick").each(function(e,t){var a=$(t),i=a.attr("class"),s;(s=i.match(/custom-color-([a-f0-9]+)/))&&(a.find(".vc_sep_line").css({borderColor:"#"+s[1]}),a.hasClass("one-line-border")&&a.find("h4").css({borderColor:"#"+s[1]})),(s=i.match(/__(.*?)__/i))&&a.find("h4").append("<span>"+s[1].replace(/-/g," ")+"</span>")});var g=300,y=function(){public_vars.$mobileMenu.addClass("visible-xs").removeClass("hidden").attr("style","display: block !important").data("height",public_vars.$mobileMenu.outerHeight()),public_vars.$mobileMenu.find(".menu-item-has-children").each(function(e,t){var a=$(t),i=a.find("> ul");i.attr("style","display: block !important").data("height",i.outerHeight()).attr("style","height: 0px; display: block")}),public_vars.$mobileMenu.attr("style","height: 0px")};public_vars.$body.find(".mobile-menu-link > a").on("click",function(e){e.preventDefault();var t=$(this);if(t.hasClass("opened")){t.removeClass("opened");var a=public_vars.$mobileMenu.find("li.visible");public_vars.$mobileMenu.add(a).removeClass("visible"),TweenLite.to(public_vars.$mobileMenu,g/1e3,{css:{height:0},delay:g/2/1e3,onComplete:function(){a.find("> ul").height(0).removeClass("visible"),a.find(".expand").removeClass("expanded")}})}else t.addClass("opened"),setTimeout(function(){public_vars.$mobileMenu.addClass("visible")},g/2),TweenLite.to(public_vars.$mobileMenu,g/1e3,{css:{height:public_vars.$mobileMenu.data("height")},ease:Power2.easeInOut,onComplete:function(){public_vars.$mobileMenu.height("")}})}),public_vars.$mobileMenu.find(".menu-item-has-children").each(function(e,t){var a=$(t),i=$('<span class="expand"><i class="entypo-plus"></i></span>'),s=a.find("> ul");a.find("> a").prepend(i),i.on("click",function(e){if(e.preventDefault(),i.hasClass("expanded")){i.removeClass("expanded");var t=s.add(s.find("ul"));t.parent().removeClass("visible"),TweenLite.to(t,g/1e3,{css:{height:0},delay:g/2/1e3,onComplete:function(){t.find(".expand").removeClass("expanded")}})}else i.addClass("expanded"),setTimeout(function(){a.addClass("visible")},g/2),TweenLite.to(s,g/1e3,{css:{height:s.data("height")},onComplete:function(){s.height("")}})})}),y(),public_vars.$body.hasClass("sticky-menu")&&setupStickMenu(),$(".lab_wpb_testimonials").each(function(t,a){var i=$(a),s=i.find(".testimonials-inner"),n=i.find(".testimonial-entry"),o=n.filter(".hidden"),r=i.data("autoswitch"),l=$('<div class="testimonials-nav">'),c=0;if(n.eq(c).addClass("current"),o.removeClass("hidden").hide(),n.length>1){for(var t=0;t<n.length;t++)l.append('<a href="#"'+(t==c?' class="active"':"")+' data-index="'+t+'">'+(t+1)+"</a>");s.append(l)}var d=function(e){if(c!=e){e%=n.length;var t=n.filter(".current"),a=n.eq(e);a.show(),t.hide();var i=a.outerHeight(!0)+l.outerHeight();t.show(),a.hide(),l.find("a").removeClass("active").eq(e).addClass("active"),TweenLite.to(t,.15,{css:{autoAlpha:0},onComplete:function(){t.attr("style","").removeClass("current").hide(),a.show().addClass("current"),TweenLite.set(a,{css:{autoAlpha:0}}),TweenLite.to(a,.35,{css:{autoAlpha:1},onComplete:function(){c=e}})}}),TweenLite.to(s,.3,{css:{height:i},onComplete:function(){s.attr("style","")}})}};if(l.on("click","a",function(e){e.preventDefault(),d(parseInt($(this).data("index"),10))}),r>0){var u=0,h=function(t){e.clearTimeout(u),t&&(u=setTimeout(function(){d(c+1),h(1)},1e3*r))};i.on("mouseover",function(){h()}).on("mouseleave",function(){h(!0)}),h(!0)}});var w=$(".cart-ribbon[data-ajax-counter]");w.length&&$.post(ajaxurl,{action:"lab_get_cart_info"},function(e){w.find(".number").html(e.count),updateHeaderCart(e.cart_subtotal,e.cart_html),0==e.count&&public_vars.$cartItems.html(e.cart_html)},"json");var C=$(".commission-rates, .my-commissions, .payment-details");if(3==C.length){C.wrapAll('<div class="myaccount-tab" id="comission-king"></div>'),C.find("table").addClass("table"),C.find('input[type="text"], textarea').addClass("form-control"),C.find(".button").addClass("btn btn-default");var T=C.find(".button[onclick]");T.parent().before(T)}var k=function(){$(".quantity").each(function(e,t){var a=$(t),i=a.find(".qty");a.hasClass("buttons_added")||(a.addClass("buttons_added"),i.before('<input type="button" value="-" class="plusminus minus">'),i.after('<input type="button" value="+" class="plusminus plus">'))})};k(),$("body").on("click",'input[type="button"].plusminus',function(){var e=$(this),t=e.prev(),a=1;e.hasClass("minus")&&(t=e.next(),a=-1);var i=parseInt(t.val(),10)+a;0>i&&(i=0),t.val(i)}),$("#yith-wcwl-form .show-title-form").on("click",function(e){e.preventDefault(),$(this).next().slideToggle("fast",function(){$(this).parent().find(".form-control ").focus()})}),$(".mobile-menu .search-form>a").on("click",function(e){e.preventDefault(),$(this).closest("form").submit()}),$("form.cart").on("submit",function(e){var t=$(this),a=t.find(".qty.text");a.length&&0==a.val()&&(e.preventDefault(),a.select())})});var a=0;$(e).resize(function(){clearTimeout(a),a=setTimeout(trigger_resizable,200)})}(jQuery,window);