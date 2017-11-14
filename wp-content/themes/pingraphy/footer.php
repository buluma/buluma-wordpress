<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Pingraphy
 */

?>
		</div>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php get_sidebar('footer'); ?>
		<div class="site-info">
			<div class="inner clearfix">
				
				<?php pingraphy_footer_copyright(); ?>
				
				<?php if( has_nav_menu('footer'))  : ?>
				<div class="menu-footer">
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_class' => 'menu clearfix' ) ); ?>
				</div>
				<?php endif; ?>
				
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<!-- Back To Top -->
<span class="back-to-top"><i class="fa fa-angle-double-up"></i></span>
<?php wp_footer(); ?>
<script>
  !function(t,o){function a(t){return function(){return sendgrid.push({m:t,args:Array.prototype.slice.call(arguments)}),sendgrid}}var sendgrid=t.sendgrid=t.sendgrid||[];if(!sendgrid.initialize){if(sendgrid.invoked)return void(t.console&&console.error&&console.error("sendgrid snippet included twice."));sendgrid.invoked=!0;for(var c=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","group","track","ready","alias","page","once","off","on"],i=0;i<c.length;i++){var p=c[i];sendgrid[p]=a(p)}sendgrid.load=function(id){var t=o.createElement("script"),a="https:"===o.location.protocol?"https://":"http://";t.type="text/javascript",t.async=!0,t.src=a+"js.labs.sendgrid.com/analytics/"+id+"/sendgrid.min.js";var c=o.getElementsByTagName("script")[0];c.parentNode.insertBefore(t,c)},sendgrid.SNIPPET_VERSION="1.0.0",
  sendgrid.load("f67b01ae-f82a-4ec3-90be-cc2764c8af52"),
  sendgrid.page()
  }}(window,document);
</script>
</body>
</html>