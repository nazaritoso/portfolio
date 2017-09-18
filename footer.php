			<?php global $theme_options; ?>
			<!-- Supersized control -->
			<div id="supersized-control"<?php if(count($theme_options["tracks"]) && (is_active_sidebar('home-left') || is_active_sidebar('home-right'))): ?> style="width: 374px;"<?php elseif(count($theme_options["tracks"]) || (is_active_sidebar('home-left') || is_active_sidebar('home-right'))): ?> style="width: 337px;"<?php endif; ?>>
				<div id="slidecaption-wrapper">
					<div id="slidecaption"></div>
				</div>
				<a href="#" id="nextslide"></a>
				<a href="#" id="prevslide"></a>
				<!-- Widget control -->
				<?php if(is_active_sidebar('home-left') || is_active_sidebar('home-right')): ?>
				<a href="#" class="widgetControl<?php echo (!(int)$theme_options["display_home_widget_on_start"] ? " inactive" : ""); ?>"></a>
				<?php endif; ?>
				<!-- /Widget control -->
				<!-- Music control -->
				<?php if(count($theme_options["tracks"])): ?>
				<a href="#" class="jPlayerControl<?php echo (!$theme_options["music_autoplay"] ? " inactive" : ""); ?>"></a>
				<?php endif; ?>
				<!-- /Music control -->
			</div>
			<!-- /Supersized control -->
		</div>
		<!-- Preloader -->
		<div id="start-preloader">
			<div>
				<p><?php global $themename; _e('Loading images...', $themename); ?></p>
			</div>
		</div>
		<!-- /Preloader -->
		<!-- Background overlay -->
		<?php if((int)$theme_options["overlay"] || !isset($theme_options["overlay"])): ?>
		<div id="background-overlay">
			<div class="background-overlay-1"></div>
		</div>
		<?php endif; ?>
		<!-- /Background overlay -->
	<?php wp_footer();?>
	<span id='jPlayer'></span>
	<span id='jPlayerPortfolio'></span>
	</body>
</html>