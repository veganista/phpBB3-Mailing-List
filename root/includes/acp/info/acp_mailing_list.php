<?php
/** 
*
* @package acp
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

class acp_mailing_list_info
{

	function module()
	{
        return array(
            'filename'	=> 'acp_mailing_list',
            'title'		=> 'ML_MAILING_LIST',
            'version'	=> '1.0.0',
            'modes'		=> array(
                /*'configure'		=> array('title' => 'ML_CONFIGURE',
                                     'auth' => 'acl_a_user',
                                     'cat' => array('ACP_GENERAL')),
*/
                'send'          => array('title' => 'ML_SEND',
                                     'auth' => 'acl_a_user',
                                     'cat' => array('ACP_GENERAL')),
                'manage'		=> array('title' => 'ML_MANAGE',
                                     'auth' => 'acl_a_user',
                                     'cat' => array('ACP_GENERAL')),
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