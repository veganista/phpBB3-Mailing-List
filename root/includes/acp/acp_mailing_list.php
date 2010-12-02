<?php
/** 
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

include($phpbb_root_path . '/includes/functions_user.'.$phpEx);

class acp_mailing_list
{
	var $u_action;
					
	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
					
		$user->add_lang('mods/mailing_list');
							
		// Set up general vars
						
		switch($mode)
		{
			case 'configure':
				$this->configure();
			break;
			
			case 'manage':
				$this->manage();
			break;
			
			case 'remove':
				$this->remove();
			break;
			
			case 'send':
				$this->send();
			break;

		}							 
	}
	

    function manage(){
	
		global $template, $db, $user, $phpEx, $phpbb_root_path, $phpbb_admin_path;
		
		$this->page_title 	= 'ML_MANAGE';
		$this->tpl_name     = 'acp_mailing_list_manage';

		$template->assign_vars(array(
				'U_ACTION'              => $this->u_action,
			)
		);

		if(isset($_POST['unsubscribe']) || isset($_POST['subscribe']))
		{
    		/*$unsubscribe = request_var('unsubscribe', array(0), true);
            for($i = 0; $i < sizeof($unsubscribe); $i++)
            {
                $data = array('mailing_list_subscribed' => 0);
                $sql = 'UPDATE ' . USERS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE user_id = ' . (int)$unsubscribe[$i];
                $db->sql_query($sql);
            }*/

    		$subscribe = request_var('subscribe', array(0), true);
            for($i = 0; $i < sizeof($subscribe); $i++)
            {
                $data = array('mailing_list_subscribed' => 1);
                $sql = 'UPDATE ' . USERS_TABLE . ' SET ' . $db->sql_build_array('UPDATE', $data) . ' WHERE user_id = ' . (int)$subscribe[$i];
                $db->sql_query($sql);
            }

		}

        /*
        //get the subscribed users
        $sql = "SELECT user_id, user_email, username 
                FROM " . USERS_TABLE . " u
                WHERE mailing_list_subscribed = 1
                AND u.user_type IN (" . USER_NORMAL . ', ' . USER_FOUNDER . ")";
        $result = $db->sql_query($sql);

        while($row = $db->sql_fetchrow($result)){
            $template->assign_block_vars('subscribers', array(
                'ID'        => $row['user_id'],
                'USERNAME'  => $row['username'],
                'EMAIL'     => $row['user_email'],
            ));
        }

        $db->sql_freeresult($result);
        */
        //get the unsubscribed users
        $sql = "SELECT user_id, user_email, username
                FROM " . USERS_TABLE . " u
                WHERE mailing_list_subscribed = 0
                AND u.user_type IN (" . USER_NORMAL . ', ' . USER_FOUNDER . ")";
        $result = $db->sql_query($sql);

        while($row = $db->sql_fetchrow($result)){
            $template->assign_block_vars('nonsubscribers', array(
                'ID'        => $row['user_id'],
                'USERNAME'  => $row['username'],
                'EMAIL'     => $row['user_email'],
            ));
        }

        $db->sql_freeresult($result);
	}
	
	function configure(){
		
		global $template, $user, $phpEx, $phpbb_admin_path, $config;
	
		$submit = (isset($_POST['submit']) ? true : false);

		if($submit)
		{

			$data = request_var('config', array('' => ''));

			//make sure that the hex codes are preceded by a # char
			if($data['ptt_colour1'][0] != '#'){
				$data['ptt_colour1'] = '#' . $data['ptt_colour1'];
			}
			if($data['ptt_colour2'][0] != '#'){
				$data['ptt_colour2'] = '#' . $data['ptt_colour2'];
			}

			$config_vars = array(
				'vars' => array(
					'ptt_tags' 			 => array('lang' => '', 'validate' => 'int',  	'type' => 'text:0:10', 		'explain' => false),
					'ptt_on' 			 => array('lang' => '', 'validate' => 'bool', 	'type' => 'radio:yes_no', 	'explain' => false),
					'ptt_max_font'		 => array('lang' => '', 'validate' => 'int',  	'type' => 'text:5:5', 		'explain' => false),
					'ptt_min_font'		 => array('lang' => '', 'validate' => 'int',  	'type' => 'text:5:5', 		'explain' => false),
					'ptt_colour1'		 => array('lang' => '', 'validate' => 'string', 'type' => 'text:0:7', 		'explain' => false),
					'ptt_colour2'		 => array('lang' => '', 'validate' => 'string', 'type' => 'text:0:7', 		'explain' => false),
					'ptt_tag_sort'		 => array('lang' => '', 'validate' => 'string', 'type' => 'text',    		'explain' => false),

				)
			);

			$error = array();
			validate_config_vars($config_vars, $data, $error);

			$validate = array(
				'ptt_tags' 		=> array('num'),
				'ptt_max_font'	=> array('num'),
				'ptt_min_font'	=> array('num'),
				'ptt_colour1'	=> array('string', false, 4, 7),
				'ptt_colour2'	=> array('string', false, 4, 7),
			);

			$error = validate_data($data, $validate);

			//custom validation
			$ct = new ColourTools();

			if(!is_numeric($data['ptt_tags'])) 			$error[] = $user->lang['PTT_TAGS_NOT_NUM'];
			if(!is_numeric($data['ptt_max_font'])) 		$error[] = $user->lang['PTT_MAX_FONT_NOT_NUM'];
			if(!is_numeric($data['ptt_min_font'])) 		$error[] = $user->lang['PTT_MIN_FONT_NOT_NUM'];

			if(!$ct->check_hex($data['ptt_colour1']))	$error[] = $user->lang['PTT_ACP_COLOUR1_INVALID'];
			if(!$ct->check_hex($data['ptt_colour2']))	$error[] = $user->lang['PTT_ACP_COLOUR2_INVALID'];



			if(!sizeof($error)){

				$booleans = array('ptt_on');

				foreach ($config_vars['vars'] as $config_name => $null)
				{
					$config_value = $data[$config_name];

					if(in_array($config_name, $booleans))
					{
						$config_value = ($config_value == 'yes' ? 1 : 0);
					}

					set_config($config_name, $config_value, false);
				}

				$message = 	$user->lang['PTT_ACP_CONF_UPDATE_SUCCESSFUL'];
				$link 	 = append_sid("index.php", "i=phpbb_topic_tagging&mode=configure");

				meta_refresh(4, $phpbb_admin_path . $link);
				trigger_error($message . adm_back_link($link));

			}

			$template->assign_vars(array(
						'ERROR'					=> implode('<br />', $error),
						'S_TAGS_ON'				=> ($data['ptt_on'] == 'yes' ? true : false),
						'TAG_AMOUNT'			=>  $data['ptt_tags'],
						'MAX_SIZE'				=>  $data['ptt_max_font'],
						'MIN_SIZE'				=>  $data['ptt_min_font'],
						'COLOUR1'				=>  $data['ptt_colour1'],
						'COLOUR2'				=>  $data['ptt_colour2'],
				)
			);

            $tag_sort_options = array(
                array('name' => $user->lang['PTT_ACP_TAG_SORT_DEFAULT'], 'value' => 'alphabetical'),
                array('name' => $user->lang['PTT_ACP_TAG_SORT_POPULAR'], 'value' => 'popular'),
                array('name' => $user->lang['PTT_ACP_TAG_SORT_RANDOM'],  'value' => 'random'),
            );

            foreach($tag_sort_options as $row){
                $template->assign_block_vars('tag_sort_options', array(
                            'NAME'          => $row['name'],
                            'VALUE'			=> $row['value'],
                            'SELECTED'   	=> $config['ptt_tag_sort'] == $row['value'] ? ' selected' : '',

                    ));
            }

		}
		else
		{

            $tag_sort_options = array(
                array('name' => $user->lang['PTT_ACP_TAG_SORT_DEFAULT'], 'value' => 'alphabetical'),
                array('name' => $user->lang['PTT_ACP_TAG_SORT_POPULAR'], 'value' => 'popular'),
                array('name' => $user->lang['PTT_ACP_TAG_SORT_RANDOM'],  'value' => 'random'),
            );

            foreach($tag_sort_options as $row){
                $template->assign_block_vars('tag_sort_options', array(
                            'NAME'          => $row['name'],
                            'VALUE'			=> $row['value'],
                            'SELECTED'   	=> $config['ptt_tag_sort'] == $row['value'] ? ' selected' : '',

                    ));
            }

			$template->assign_vars(array(
						'S_TAGS_ON'				=> ($config['ptt_on'] == 1 ? true : false),
						'TAG_AMOUNT'			=>  $config['ptt_tags'],
						'MAX_SIZE'				=>  $config['ptt_max_font'],
						'MIN_SIZE'				=>  $config['ptt_min_font'],
						'COLOUR1'				=>  $config['ptt_colour1'],
						'COLOUR2'				=>  $config['ptt_colour2'],

				)
			);
		}

        $this->page_title 	= 'ML_CONFIGURE';
        $this->tpl_name		= 'acp_mailing_list_configure';
	}
	
	function remove()
	{
		
		global $template, $user, $phpbb_admin_path;
		
		die('remove');
	
	}
	
	function send()
	{
		global $config, $db, $user, $auth, $template, $cache, $id;
		global $phpbb_root_path, $phpbb_admin_path, $phpEx, $table_prefix;


		$user->add_lang('acp/email');
		$this->tpl_name = 'acp_mailing_list_send';
		$this->page_title = 'ML_SEND';

		$form_key = 'acp_mailing_list';
		add_form_key($form_key);

		// Set some vars
		$submit = (isset($_POST['submit'])) ? true : false;
		$error = array();

		$subject	= utf8_normalize_nfc(request_var('subject', '', true));
		$message	= utf8_normalize_nfc(request_var('message', '', true));

        $sql = 'SELECT username, username_clean, user_email, user_jabber, user_notify_type, user_lang
                FROM ' . USERS_TABLE . '
                WHERE mailing_list_subscribed = 1
                    AND user_type IN (' . USER_NORMAL . ', ' . USER_FOUNDER . ')
                ORDER BY user_lang, user_notify_type';

        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);

        if (!$row)
        {
            $error[] = $user->lang['ML_NO_SUBSCRIBERS'];
        }

        // Do the job ...
		if ($submit)
		{
			// Error checking needs to go here ... if no subject and/or no message then skip
			// over the send and return to the form
			$use_queue		= (isset($_POST['send_immediately'])) ? false : true;
			$priority		= request_var('mail_priority_flag', MAIL_NORMAL_PRIORITY);

			if (!check_form_key($form_key))
			{
				$error[] = $user->lang['FORM_INVALID'];
			}

			if (!$subject)
			{
				$error[] = $user->lang['NO_EMAIL_SUBJECT'];
			}

			if (!$message)
			{
				$error[] = $user->lang['NO_EMAIL_MESSAGE'];
			}

			if (!sizeof($error))
			{

				$i = $j = 0;

				// Send with BCC, no more than 50 recipients for one mail (to not exceed the limit)
				$max_chunk_size = 50;
				$email_list = array();
				$old_lang = $row['user_lang'];
				$old_notify_type = $row['user_notify_type'];

				do
				{
					if (($row['user_notify_type'] == NOTIFY_EMAIL && $row['user_email']) ||
						($row['user_notify_type'] == NOTIFY_IM && $row['user_jabber']) ||
						($row['user_notify_type'] == NOTIFY_BOTH && ($row['user_email'] || $row['user_jabber'])))
					{
						if ($i == $max_chunk_size || $row['user_lang'] != $old_lang || $row['user_notify_type'] != $old_notify_type)
						{
							$i = 0;

							if (sizeof($email_list))
							{
								$j++;
							}

							$old_lang = $row['user_lang'];
							$old_notify_type = $row['user_notify_type'];
						}

						$email_list[$j][$i]['lang']		= $row['user_lang'];
						$email_list[$j][$i]['method']	= $row['user_notify_type'];
						$email_list[$j][$i]['email']	= $row['user_email'];
						$email_list[$j][$i]['name']		= $row['username'];
						$email_list[$j][$i]['jabber']	= $row['user_jabber'];
						$i++;
					}
				}
				while ($row = $db->sql_fetchrow($result));
				$db->sql_freeresult($result);

				// Send the messages
				include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
				include_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
				$messenger = new messenger($use_queue);

				$errored = false;

				for ($i = 0, $size = sizeof($email_list); $i < $size; $i++)
				{
					$used_lang = $email_list[$i][0]['lang'];
					$used_method = $email_list[$i][0]['method'];

					for ($j = 0, $list_size = sizeof($email_list[$i]); $j < $list_size; $j++)
					{
						$email_row = $email_list[$i][$j];

						$messenger->{((sizeof($email_list[$i]) == 1) ? 'to' : 'bcc')}($email_row['email'], $email_row['name']);
						$messenger->im($email_row['jabber'], $email_row['name']);
					}

                                    
					$messenger->template('mailing_list', $used_lang);

					$messenger->headers('X-AntiAbuse: Board servername - ' . $config['server_name']);
					$messenger->headers('X-AntiAbuse: User_id - ' . $user->data['user_id']);
					$messenger->headers('X-AntiAbuse: Username - ' . $user->data['username']);
					$messenger->headers('X-AntiAbuse: User IP - ' . $user->ip);

					$messenger->subject(htmlspecialchars_decode($subject));
					$messenger->set_mail_priority($priority);

                    
                    /* Get the */


					$messenger->assign_vars(array(
						'CONTACT_EMAIL' => $config['board_contact'],
						'MESSAGE'		=> htmlspecialchars_decode($message),
                        'U_OPT_OUT'     => generate_board_url() . "/ucp.{$phpEx}?i=mailing_list&mode=prefs}",
					));

					if (!($messenger->send($used_method)))
					{
						$errored = true;
					}
				}
				unset($email_list);

				$messenger->save_queue();


                //$usernames = explode("\n", $usernames);
                //add_log('admin', 'LOG_MASS_EMAIL', implode(', ', utf8_normalize_nfc($usernames)));
				

				if (!$errored)
				{
					$message = ($use_queue) ? $user->lang['EMAIL_SENT_QUEUE'] : $user->lang['EMAIL_SENT'];
					trigger_error($message . adm_back_link($this->u_action));
				}
				else
				{
					$message = sprintf($user->lang['EMAIL_SEND_ERROR'], '<a href="' . append_sid("{$phpbb_admin_path}index.$phpEx", 'i=logs&amp;mode=critical') . '">', '</a>');
					trigger_error($message . adm_back_link($this->u_action), E_USER_WARNING);
				}
			}
		}

		$s_priority_options = '<option value="' . MAIL_LOW_PRIORITY . '">' . $user->lang['MAIL_LOW_PRIORITY'] . '</option>';
		$s_priority_options .= '<option value="' . MAIL_NORMAL_PRIORITY . '" selected="selected">' . $user->lang['MAIL_NORMAL_PRIORITY'] . '</option>';
		$s_priority_options .= '<option value="' . MAIL_HIGH_PRIORITY . '">' . $user->lang['MAIL_HIGH_PRIORITY'] . '</option>';

		$template->assign_vars(array(
			'S_WARNING'				=> (sizeof($error)) ? true : false,
			'WARNING_MSG'			=> (sizeof($error)) ? implode('<br />', $error) : '',
			'U_ACTION'				=> $this->u_action,
			'U_FIND_USERNAME'		=> append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=searchuser&amp;form=acp_email&amp;field=usernames'),
			'SUBJECT'				=> $subject,
			'MESSAGE'				=> $message,
			'S_PRIORITY_OPTIONS'	=> $s_priority_options)
		);

	}
	
}
			
?>