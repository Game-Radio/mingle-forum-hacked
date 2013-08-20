<?php
	global $user_ID;
	if(($user_ID || $this->allow_unreg()) && $this->have_access($this->check_parms($_GET['forum'])) && $this->have_access($this->check_parms($_GET['forum']), 'post'))
	{
		$parser = new cartpaujBBCodeParser();
		$this->current_view = NEWTOPIC;
		$out = $this->header();
		$out .= "<form action='".WPFURL."wpf-insert.php' name='addform' method='post' enctype='multipart/form-data'>";
		$out .= "<table class='wpf-table' width='100%'>
			<tr>
				<th colspan='2'>".__("Post new Topic", "mingleforum")."</th>
			</tr>
			<tr>
				<td>".__("Subject:", "mingleforum")."</td>
				<td><input size='50%' type='text' name='add_topic_subject' class='wpf-input' /></td>
			</tr>
			<tr>
				<td valign='top'>".__("Message:", "mingleforum")."</td>
				<td>
					".$parser->get_editor()."
				</td>
			</tr>";
				$out .= apply_filters('wpwf_form_guestinfo',''); //--weaver--
				$out .= $this->get_captcha();
				if($this->options['forum_allow_image_uploads'])
				{
					$out .= "
					<tr>
						<td valign='top'>".__("Images:", "mingleforum")."</td>
						<td colspan='2'>
							<input type='file' name='mfimage1' id='mfimage' /><br/>
							<input type='file' name='mfimage2' id='mfimage' /><br/>
							<input type='file' name='mfimage3' id='mfimage' /><br/>
						</td>
					</tr>";
				}
				$out .= "
			<tr>
				<td></td>
				<td><input type='submit' id='wpf-post-submit' name='add_topic_submit' value='".__("Submit", "mingleforum")."' /></td>
				<input type='hidden' name='add_topic_forumid' value='".$this->check_parms($_GET['forum'])."'/>
				<input type='hidden' name='add_topic_plink' value='".get_permalink($this->page_id)."'/>
			</tr>
			</table></form>";
		$this->o .= $out;
	}
	else
		wp_die(__("Sorry. you don't have permission to post.", "mingleforum"));
