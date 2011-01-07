<?php

/**
 * @author Edge Technology Consulting
 * @copyright 2009
 */

function displayMonths() {
    ?>
<option value="Jan">January</option>
<option value="Feb">February</option>
<option value="Mar">March</option>
<option value="Apr">April</option>
<option value="May">May</option>
<option value="Jun">June</option>
<option value="Jul">July</option>
<option value="Aug">August</option>
<option value="Sep">September</option>
<option value="Oct">October</option>
<option value="Nov">November</option>
<option value="Dec">December</option>
<?php
}
  

function displaySelectionBox($start_month = '', $start_day = '', $start_year = '', $end_month = '', $end_day = '', $end_year = '') {

	$currentyear = date ( 'Y' );

	?>Start Date:
<select name="start_month">
    <?php
	if ($start_month != '') {
		echo "<option value=\"$start_month\">$start_month</option>";
	}
	displayMonths ();
	?>
	</select>

<select name="start_day">
    <?php
	if ($start_day != '') {
		echo "<option value=\"$start_day\">$start_day</option>";
	}
	for($i = 1; $i <= 31; $i ++) {
		echo "<option value=\"$i\">$i</option>";
	}
	?>
	</select>

<select name="start_year">
    <?php
	if ($start_year != '') {
		echo "<option value=\"$start_year\">$start_year</option>";
	}
	for($i = $currentyear; $i <= $currentyear + 5; $i ++) {
		echo "<option value=\"$i\">$i</option>";
	}
	?>
	</select>

- End Date:
<select name="end_month">
     <?php
	if ($end_month !== '') { //PPAY
            ?>
            <option value="<?php echo $end_month; ?>"><?php echo $end_month; ?></option>
            <?php
	}
	displayMonths ();
	?>
</select>

<select name="end_day">
    <?php
	if ($end_day != '') {
		echo "<option value=\"$end_day\">$end_day</option>";
	}
	for($i = 1; $i <= 31; $i ++) {
		echo "<option value=\"$i\">$i</option>";
	}
	?>
	</select>

<select name="end_year">
    <?php
	if ($end_year != '') {
		echo "<option value=\"$end_year\">$end_year</option>";
	}
	for($i = $currentyear; $i <= $currentyear + 5; $i ++) {
		echo "<option value=\"$i\">$i</option>";
	}
	?>
	</select>
<?php
}

?>