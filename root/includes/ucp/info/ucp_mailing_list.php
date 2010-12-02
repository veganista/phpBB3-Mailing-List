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
* @package module_install
*/
class ucp_mailing_list_info
{
	function module()
	{
		return array(
			'filename'	=> 'ucp_mailing_list',
			'title'		=> 'UCP_MAILING_LIST',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'prefs'	=> array('title' => 'UCP_ML_PREFERENCES', 'auth' => '', 'cat' => array('UCP_MAILING_LIST')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>