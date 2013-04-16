<p><label><?php _e( 'Title', self::LOCALE ); ?>: <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" class="widefat" /></label></p>

<p><label><?php _e( 'City', self::LOCALE ); ?>: <input type="text" id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" value="<?php echo $city; ?>" class="widefat" /></label></p>

<p><label><?php _e( 'Country', self::LOCALE ); ?>: <select id="<?php echo $this->get_field_id( 'country' ); ?>" name="<?php echo $this->get_field_name( 'country' ); ?>" class="">
	<?php
		foreach ($countries as $key => $value):
	?>
	<option value="<?php echo $key; ?>"<?php
		if ($key === $country):
			echo ' selected';
		endif;
	?>><?php echo $value; ?></option>
	<?php
		endforeach;
	?>
</select></label></p>

<fieldset>
	<legend><?php _e( 'Units', self::LOCALE ); ?>:</legend>
	<p><input type="radio" id="<?php echo $this->get_field_id( 'units' ) . '-1'; ?>" name="<?php echo $this->get_field_name( 'units' ); ?>" value="imperial" class="radio" <?php echo $units[0]; ?>/> <label for="<?php echo $this->get_field_id( 'units' ) . '-1'; ?>"><?php _e( 'Imperial', self::LOCALE ); ?></label>
	<br>
	<input type="radio" id="<?php echo $this->get_field_id( 'units' ) . '-2'; ?>" name="<?php echo $this->get_field_name( 'units' ); ?>" value="metric" class="radio" <?php echo $units[1]; ?>/> <label for="<?php echo $this->get_field_id( 'units' ) . '-2'; ?>"><?php _e( 'Metric', self::LOCALE ); ?></label></p>
</fieldset>

<input type="hidden" id="<?php echo $this->get_field_id( 'lang' ); ?>" name="<?php echo $this->get_field_name( 'lang' ); ?>" value="<?php echo $lang; ?>" />