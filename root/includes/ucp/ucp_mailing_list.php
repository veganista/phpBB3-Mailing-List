<?php
/**
*
* @package ucp
* @version $Id$
* @copyright (c) 2005 phpBB Group
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

/**
* ucp_prefs
* Changing user preferences
* @package ucp
*/
class ucp_mailing_list
{
	var $u_action;

	function main($id, $mode)
	{
		global $config, $db, $user, $auth, $template, $phpbb_root_path, $phpEx;

		$submit = (isset($_POST['submit'])) ? true : false;
		$error = $data = array();
		$s_hidden_fields = '';

		$user->add_lang('mods/mailing_list');

		switch ($mode)
		{
			case 'prefs':
				add_form_key('ucp_mailing_list');
				$data = array(
					'mailing_list_subscribed'	=> request_var('mailing_list_subscribed', 0),
				);


				if ($submit)
				{

					if (!check_form_key('ucp_mailing_list'))
					{
						$error[] = 'FORM_INVALID';
					}

					if (!sizeof($error))
					{
						$sql_ary = array(
							'mailing_list_subscribed' => $data['mailing_list_subscribed'],
						);

						$sql = 'UPDATE ' . USERS_TABLE . '
							SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
							WHERE user_id = ' . $user->data['user_id'];
						$db->sql_query($sql);

						meta_refresh(3, $this->u_action);
						$message = $user->lang['PREFERENCES_UPDATED'] . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $this->u_action . '">', '</a>');
						trigger_error($message);
					}

					// Replace "error" strings with their real, localised form
					$error = preg_replace('#^([A-Z_]+)$#e', "(!empty(\$user->lang['\\1'])) ? \$user->lang['\\1'] : '\\1'", $error);
				}

				$s_custom = false;

				$template->assign_vars(array(
					'ERROR'				=> (sizeof($error)) ? implode('<br />', $error) : '',

					'S_ALLOW_EMAIL'	=> ($data['mailing_list_subscribed'] == 1) ? true : false,
				));

			break;

		}

		$template->assign_vars(array(
			'L_TITLE'			=> $user->lang['ML_MAILING_LIST'],//$user->lang['UCP_PREFS_' . strtoupper($mode)],

			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			'S_UCP_ACTION'		=> $this->u_action)
		);

		$this->tpl_name = 'ucp_mailing_list';
		$this->page_title = 'ML_MAILING_LIST';
	}
}

?>