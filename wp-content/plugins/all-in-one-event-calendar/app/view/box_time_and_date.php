<?php wp_nonce_field( 'ai1ec', AI1EC_POST_TYPE ); ?>
<h4 class="ai1ec-section-title"><?php _e( 'Event date and time', AI1EC_PLUGIN_NAME ); ?></h4>
<table class="ai1ec-form">
	<tbody>
		<tr>
			<td class="ai1ec-first">
				<label for="ai1ec_all_day_event">
					<?php _e( 'All-day event', AI1EC_PLUGIN_NAME ); ?>?
				</label>
			</td>
			<td>
				<input type="checkbox" name="ai1ec_all_day_event" id="ai1ec_all_day_event" value="1" <?php echo $all_day_event; ?> />
			</td>
		</tr>
		<tr>
			<td>
				<label for="ai1ec_start-date-input">
					<?php _e( 'Start date / time', AI1EC_PLUGIN_NAME ); ?>:
				</label>
			</td>
			<td>
				<input type="text" class="ai1ec-date-input" id="ai1ec_start-date-input" />
				<input type="text" class="ai1ec-time-input" id="ai1ec_start-time-input" />
				<small><?php echo $timezone ?></small>
				<input type="hidden" name="ai1ec_start_time" id="ai1ec_start-time" value="<?php echo $start_timestamp ?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="ai1ec_end-date-input">
					<?php _e( 'End date / time', AI1EC_PLUGIN_NAME ) ?>:
				</label>
			</td>
			<td>
				<input type="text" class="ai1ec-date-input" id="ai1ec_end-date-input" />
				<input type="text" class="ai1ec-time-input" id="ai1ec_end-time-input" />
				<small><?php echo $timezone ?></small>
				<input type="hidden" name="ai1ec_end_time" id="ai1ec_end-time" value="<?php echo $end_timestamp ?>" />
			</td>
		</tr>
		<tr>
			<td>
			  <input type="checkbox" name="ai1ec_repeat" id="ai1ec_repeat" value="1" <?php echo $repeating_event ? 'checked="checked"' : '' ?>/>
			  <input type="hidden" name="ai1ec_rrule" id="ai1ec_rrule" value="<?php echo $rrule ?>" />
				<label for="ai1ec_repeat" id="ai1ec_repeat_label">
					<?php _e( 'Repeat', AI1EC_PLUGIN_NAME ); echo $repeating_event ? ':' : '...' ?>
				</label>
			</td>
			<td>
			  <div id="ai1ec_repeat_text">
			    <a href="#ai1ec_repeat_box"><?php echo $rrule_text ?></a>
			  </div>
			</td>
		</tr>
    
    <div id="ai1ec_repeat_box">
      <ul class="ai1ec_repeat_tabs">
        <li><a href="#ai1ec_daily_content" id="ai1ec_daily_tab" class="ai1ec_tab ai1ec_active"><?php _e( 'Daily', AI1EC_PLUGIN_NAME ) ;?></a></li>
        <li><a href="#ai1ec_weekly_content" id="ai1ec_weekly_tab" class="ai1ec_tab"><?php _e( 'Weekly', AI1EC_PLUGIN_NAME ) ;?></a></li>
        <li><a href="#ai1ec_monthly_content" id="ai1ec_monthly_tab" class="ai1ec_tab"><?php _e( 'Monthly', AI1EC_PLUGIN_NAME ) ;?></a></li>
        <li><a href="#ai1ec_yearly_content" id="ai1ec_yearly_tab" class="ai1ec_tab"><?php _e( 'Yearly', AI1EC_PLUGIN_NAME ) ;?></a></li>
      </ul>
      <div style="clear:both;"></div>
      <div id="ai1ec_daily_content" class="ai1ec_tab_content" title="daily">
        <?php echo $row_daily ?>
        <div id="ai1ec_repeat_tab_append">
          <div id="ai1ec_ending_box" class="ai1ec_repeat_centered_content">
        		<div id="ai1ec_end_holder">
        		  <label for="ai1ec_end">
        				<?php _e( 'End', AI1EC_PLUGIN_NAME ) ?>:
        			</label>
        			 <?php echo $end ?>
        		</div>
        		<div style="clear:both;"></div>
        		<div id="ai1ec_count_holder">
        		  <label for="ai1ec_count">
        				<?php _e( 'Ending after', AI1EC_PLUGIN_NAME ) ?>:
        			</label>
        			<?php echo $count; ?>
        		</div>
        		<div style="clear:both;"></div>
        		<div id="ai1ec_until_holder">
        		  <label for="ai1ec_until-date-input">
        				<?php _e( 'On date', AI1EC_PLUGIN_NAME ) ?>:
        			</label>
        			<input type="text" class="ai1ec-date-input" id="ai1ec_until-date-input" />
        			<input type="hidden" name="ai1ec_until_time" id="ai1ec_until-time" value="<?php echo !is_null( $until ) && $until > 0 ? $until : '' ?>" />
        		</div>
        		<div style="clear:both;"></div>
        	</div>
        	<div id="ai1ec_apply_button_holder">
            <input type="button" name="ai1ec_none_button" value="<?php _e( 'Apply', AI1EC_PLUGIN_NAME ) ;?>" class="ai1ec_repeat_apply button button-highlighted" />
            <a href="#ai1ec_cancel" class="ai1ec_repeat_cancel"><?php _e( 'Cancel', AI1EC_PLUGIN_NAME ) ?></a>
          </div>
          <div style="clear:both;"></div>
        </div>
        <div style="clear:both;"></div>
      </div>
      <div id="ai1ec_weekly_content" class="ai1ec_tab_content" title="weekly">
        <?php echo $row_weekly ?>
      </div>
      <div id="ai1ec_monthly_content" class="ai1ec_tab_content" title="monthly">
        <?php echo $row_monthly ?>
      </div>
      <div id="ai1ec_yearly_content" class="ai1ec_tab_content" title="yearly">
        <?php echo $row_yearly ?>
      </div>
      <div style="clear:both;"></div>
    </div>
		
	</tbody>
</table>
