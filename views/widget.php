<?php
	if ( ! empty( $title ) ):
		echo $before_title . $title . $after_title;
	endif;
?>
<p class="<?php echo self::SLUG; ?>-p"><span class="<?php echo self::SLUG; ?>-location"><?php echo $city; ?></span> 
<span class="<?php echo self::SLUG; ?>-time"><?php echo $weather_date; ?></span>
<span class="<?php echo self::SLUG; ?>-temps"><span class="<?php echo self::SLUG; ?>-temp"></span> <span class="<?php echo self::SLUG; ?>-high-low"><span class="<?php echo self::SLUG; ?>-high"></span> <span class="<?php echo self::SLUG; ?>-low"></span></span></span>
<span class="<?php echo self::SLUG; ?>-attr">Weather data from <a href="http://openweathermap.org/" rel="external">Open Weather Map</a></span></p>
<script>
	var currentWeatherWidgetData = {
		city: '<?php echo $city; ?>',
		country: '<?php echo $country; ?>',
		units: '<?php echo $units; ?>',
		lang: '<?php echo $lang; ?>'
	};
</script>