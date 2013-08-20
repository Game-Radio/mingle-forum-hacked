<?php
if(function_exists('home_url'))
{
/*************** wpf-add-forum.php *********************/
	global $mingleforum;
	$groupmane = isset($_GET['groupid']) ? $_GET['groupid'] : 0;
	$groupname = stripslashes($mingleforum->get_groupname($groupmane));
	$groupname = empty($groupname) ? __("Add category", "mingleforum") : __("Add forum to", "mingleforum").' "'.$groupname.'"';
	echo "<h2><img src='".WPFURL."images/table.png' />$groupname</h2>";

	echo "<form name='add_forum_form' id='add_forum_form' method='post' action='admin.php?page=mfstructure&mingleforum_action=structure'>";
	echo "<table class='form-table'>
			<tr>
				<th>".__("Name:", "mingleforum")."</th>
				<td><input type='text' value='' name='add_forum_name' /></td>
			</tr>
			<tr>
				<th>".__("Description:", "mingleforum")."</th>
				<td><textarea name='add_forum_description' ".ADMIN_ROW_COL."></textarea></td>
			</tr>
			<tr>";

	  $gr_id = (isset($_GET['groupid']) && is_numeric($_GET['groupid']))?$_GET['groupid']:0;
			echo "<tr>
				<th></th>
				<td><input type='submit' value='".__("Save forum", "mingleforum")."' name='add_forum_submit' /></td>
			</tr>
			<input type='hidden' name='add_forum_group_id' value='{$gr_id}' />";
	echo "</form></table>";
/**********************************************************/
}
else
{
	echo '<p>Nice TRY!</p>';
}
