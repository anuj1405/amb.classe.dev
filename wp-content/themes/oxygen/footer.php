<?php if( ! defined("NO_HEADER_MENU")): ?>
<?php endif; ?>

<!--<?php if( ! defined("NO_FOOTER_MENU")): ?>

	<?php get_template_part('tpls/footer-main'); ?>
.
<?php endif; ?>
</div>-->
		
		
		
		
<div class="footer-bottom">
	<div class="footer-bottom">
		<ul class="footer-new">
		<li>
			<div class="form text-center">
				<p  style="padding-top: 4px;">N E W S L E T T E R </p>
				<form id="subscribe">
					<input class="email2" id="email" value="" name="email" required placeholder="Email Address" type="email" style="    width: 312px;
    color: #000;
    background: rgb(231, 231, 231);
    border: 1px solid #a2a2a2;
    height: 26px;
">
			         <input class="btn2" type="submit" value="Subscribe">
			              <!-- <input type="text" id="test" name="test" value=""> -->
			     </form>
			    
			    <div id="thankYou1">
					<p>You have been successfully added to our mailing list, keeping you up-to-date with our latest news.</p>
					<p>Keep ON your Fashion ALERT! </p>
				</div>
				<div id="Sorry1">
					<p>Please Try Again.</p>
				</div>
	         </div> 
	    </li>
					
		<li>	
			<div class=" text-center">  
				<a href="http://shop.ambclasse.com/contact-us/ ">	
					<p style="padding-top: 10px;"> S T O R E S</p>
					<img src="http://shop.ambclasse.com/wp-content/uploads/2016/08/mark.jpg">
				</a>
			</div> 
		</li>
	</ul>				
	<div class="clearfix"></div>			
	</div>
		<div class="footer">
			<div class="footer-top">
				<div class="container">
					<div class="col-md-4 footer-grid">
						<h5>About Us</h5>
						<p>Amb Classe is the leading manufacturer of readymade and stylish fashionwear. Specializing in high End clothing for Men, we are a fast
growing superbrand foraying soon into Women and Kids Wear. Our Expertise lies in blending high quality fabric with new age designs to
suit today’s fashion conscious customer. Having established a vast network of retail stores, we are now creating a personalized online
shopping experience for you.</p>
			  		</div>
					<div class="col-md-1 footer-grid">
					&nbsp;
					</div>
					<div class="col-md-3 footer-grid">
						<h5>Help</h5>
					
						<ul class="help-footer" style="list-style:none;">

							<li><a target="_blank" href="http://blog.ambclasse.com" style="color: #d8d0d4;">Blog</a></li>

							<!-- <li><a target="blank" href="http://blogs.ambclasse.com" style="color: #000;">Blogs</a></li> -->
							<li><a  href="http://shop.ambclasse.com/terms-conditions/" style="color: #d8d0d4;">Terms And Conditions</a></li>
							<li><a  href="http://shop.ambclasse.com/privacy-policy/" style="color: #d8d0d4;">Privacy Policy</a></li>
							<li><a  href="http://shop.ambclasse.com/orders-returns/" style="color: #d8d0d4;">Returns</a></li>
							<li><a  href="http://shop.ambclasse.com/affiliates/" style="color: #d8d0d4;">Affiliates</a></li>
							<li><a  href="/sitemap" style="color: #d8d0d4;">Sitemap</a></li>
		  					<li ><a  href="http://shop.ambclasse.com/f-a-q/" style="color: #d8d0d4;">FAQ</a></li>
						</ul>
						
					</div>
  
					<div class="col-md-4 footer-grid">
						<h5>Follow Us</h5>
						<p>Follow us on Social Media for more content & behind the scenes from the Amb Classe team!<br> Use <b><font color="#a1925a">#AmbClasse</font></b> Or <b><font color="#a1925a">#WearYourBrand</font></b> to share your own Fashion Show content.</p>
								
						<div class="social-icons footer-social-icons">
							<a href="https://www.facebook.com/ambclasse/?fref=ts" target="_blank"><img src="http://shop.ambclasse.com/wp-content/uploads/2016/07/footer-ico1.png "></a>
							 &nbsp; <a href="https://twitter.com/ambclasse"  target="_blank"><img src="http://shop.ambclasse.com/wp-content/uploads/2016/07/footer-ico2.png "></a> &nbsp; 
							<a href="#"  target="_blank"><img src="http://shop.ambclasse.com/wp-content/uploads/2016/07/footer-ico3.png "></a>
							<p><a href="mailto:amb.classe.enterprise@gmail.com" style="color:#776340;">amb.classe.enterprise@gmail.com</a></p>
						</div>
					</div>
				<div class="clearfix"></div>  
				</div>
			</div>
	</div>
</div>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> -->
<script>
jQuery(function($){
	("COMING!");
    $("#thankYou1").hide();
    $("#Sorry1").hide();
});
</script>

<script>
jQuery('#subscribe').submit(function (e) {
	console.log("CONSOLE LOG");
	e.preventDefault();
	 var data1 =   
	 {
        email :  jQuery("input[name=email]").val()
     };
    

 jQuery.ajax({
        url: 'http://www.ambclasse.com/subscribe',
        data: data1,

        type: 'POST',
       crossDomain: true,
        success: function(data){
        	// console.log(data);
        	jQuery('#subscribe').hide();
        	jQuery('#thankYou1').show();
            // console.log("success subsc");
        },
        error: function(data){

        	jQuery('#Sorry1').show();
        	// $('#subscribe').hide();
        	// console.log("error subs");
        }
    });
});
</script>
<!-- //footer-section -->



	<?php wp_footer(); ?>

</body>

	
</html>