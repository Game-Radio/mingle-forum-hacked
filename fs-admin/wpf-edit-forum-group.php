<?php
if(function_exists('home_url'))
{
if(isset($_POST['edit_save_group'])){
	global $wpdb, $table_prefix;
	$usergroups = isset($_POST['usergroups'])?$_POST['usergroups']:"";
	$usercanpost = isset($_POST['usercanpost'])?$_POST['usercanpost']:"";
	$edit_group_name = isset($_POST['edit_group_name'])?$wpdb->escape($_POST['edit_group_name']):"";
	$edit_group_description = isset($_POST['edit_group_description'])?$wpdb->escape($_POST['edit_group_description']):"";
	$edit_group_id = isset($_POST['edit_group_id'])?$_POST['edit_group_id']:"";

	if($_POST['edit_group_name'] == "")
		echo "<div id='message' class='updated fade'><p>".__("You must specify a forum name", "mingleforum")."</p></div>";

	global $wpdb, $table_prefix;
	$wpdb->query("UPDATE ".$table_prefix."forum_forums SET name = '$edit_group_name', description = '$edit_group_description' WHERE id = $edit_group_id" );

	$this->update_usergroups($usergroups, $edit_group_id);
	$this->update_usercanpost($usercanpost, $edit_group_id);
	echo "<div id='message' class='updated fade'><p>".__("Forum updated successfully", "mingleforum")."</p></div>";
}

if(($_GET['do'] == "editgroup") && (!isset($_POST['edit_save_group']))){
	$gr_id = (is_numeric($_GET['groupid']))?$_GET['groupid']:0;
	global $mingleforum;
	$usergroups = $mingleforum->get_usergroups();
	$usergroups_with_access = $this->get_usersgroups_with_access_to_group($gr_id);
	$usergroups_can_post = $this->get_usersgroups_with_access_to_post($gr_id);
	$group_name = stripslashes($mingleforum->get_groupname($gr_id));
	global $wpdb, $table_prefix;

	echo "<h2>".__("Edit forum", "mingleforum")." \"$group_name\"</h2>";

	echo "<form name='edit_group_form' method='post' action=''>";

	echo "<table class='form-table'>
			<tr>
				<th>".__("Name:", "mingleforum")."</th>
				<td><input type='text' value='$group_name' name='edit_group_name' /></td>
			</tr>
			<tr>
				<th>".__("Description", "mingleforum")."</th>
				<td><textarea name='edit_group_description' ".ADMIN_ROW_COL.">".stripslashes($mingleforum->get_group_description($gr_id))."</textarea></td>
			</tr>
			<tr>
				<th>".__("User Groups:", "mingleforum")."</th>
				<td>";

						echo "<strong>".__("Members of the checked User Groups have access to the forums in this category:", "mingleforum")."</strong>&nbsp;";
						if($usergroups){
							$i = 0;
							echo "<table class='wpf-wide'>";
							echo "<tr>";

							foreach($usergroups as $usergroup){
								$col = 4;
								if($mingleforum->array_search($usergroup->id, $usergroups_with_access))
									$checked = "checked='checked'";
								else
									$checked = "";
								$e = "<p><input type='checkbox' $checked name='usergroups[]' value='$usergroup->id'/> ".stripslashes($usergroup->name)."</p>\n\r";

								if($i == 0){
									echo "<td>$e";
									++$i;
								}
								elseif($i < $col){
									echo "$e";
									++$i;
								}
								else{
									echo "$e</td>";
									$i = 0;
								}

							}
							echo "</tr></table>";
						}

						else
							echo __("There are no User Groups", "mingleforum");


				echo "</td>
			</tr>
			<tr>
				<th>".__("User Groups:", "mingleforum")."</th>
				<td>";

						echo "<strong>".__("Members of the checked User Groups can post to the forums in this category:", "mingleforum")."</strong>&nbsp;";
						if($usergroups){
							$i = 0;
							echo "<table class='wpf-wide'>";
							echo "<tr>";

							foreach($usergroups as $usergroup){
								$col = 4;
								$checked = "";
								if($mingleforum->array_search($usergroup->id, $usergroups_can_post))
									$checked = "checked='checked'";
								$e = "<p><input type='checkbox' $checked name='usercanpost[]' value='$usergroup->id'/> ".stripslashes($usergroup->name)."</p>\n\r";

								if($i == 0){
									echo "<td>$e";
									++$i;
								}
								elseif($i < $col){
									echo "$e";
									++$i;
								}
								else{
									echo "$e</td>";
									$i = 0;
								}

							}
							echo "</tr></table>";
						}

						else
							echo __("There are no User Groups", "mingleforum");


				echo "</td>
			</tr>
			<tr>
				<th></th>
				<td><input type='submit' name='edit_save_group' value='".__("Save forum", "mingleforum")."' /></td>
			</tr>

			<input type='hidden' name='edit_group_id' value='".$gr_id."' />";

	echo "</table>";

	echo "</form>";

}

}
else
{
	echo '<p>Nice TRY!</p>';
}
