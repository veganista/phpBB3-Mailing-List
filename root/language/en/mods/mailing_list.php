<?php
/** 
*
* example [English]
*
* @package language
* @version $Id: phpbb_topic_tagging_lang.php,v 0.0.0 2007/09/24 16:13:01 nanothree Exp $
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
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
    'ML_ACTIONS'                    => 'Actions',
	'ML_MAILING_LIST'				=> 'Mailing List',
    'ML_CONFIGURE'              	=> 'Configure',
    'ML_SEND'                       => 'Send',
    'ML_MANAGE'                     => 'Manage',
    'ML_UNSUBSCRIBE_USER'           => 'Unsubscribe User',
    'ML_MASS_EMAIL_ALL'             => 'Should mass emails only send to the mailing list?',
    'ML_MASS_EMAIL_ALL_EXPLAIN'     => 'Choose wether the mass e-mail option under the "System" tab should send to every including those who have opted-out of the mailing list or only those on the mailing list',
    'ML_OPTIONS'                    => 'Options',
    'ML_MANAGE_MAILING_LIST'        => 'Manage Mailing List',
    'ML_MANAGE_MANAGE_DESCRIPTION'  => 'Here you can subscribe and unsubscribe members of your forum to your mailing list',
    'ML_NO_SUBSCRIBERS'             => 'No Subscribers',
    'ML_SUBSCRIBE'                  => 'Subscribe',
    'ML_UNSUBSCRIBE'                => 'Unsubscribe',
    'ML_SUBSCRIBERS'                => 'Subscribers',
    'ML_NON_SUBSCRIBERS'            => 'Non-Subscribers',
    'ML_NO_NON_SUBSCRIBERS'         => 'No non-subscribers',
    'ML_EMAIL_ADDRESS'              => 'Email Address',
    'ML_USERNAME'                   => 'Username',
    'ML_SEND_EXPLAIN'               => 'Here you can e-mail a message to all users who have <strong>NOT</strong> opted out of your mailing list. To achieve this an e-mail will be sent out to the administrative e-mail address supplied, with a blind carbon copy sent to all recipients. The default setting is to only include 50 recipients in such an e-mail, for more recipients more e-mails will be sent. If you are emailing a large group of people please be patient after submitting and do not stop the page halfway through. It is normal for a mass emailing to take a long time, you will be notified when the script has completed.',
    'ML_NO_SUBSCRIBERS'             => 'There are no subscribers on your mailing list',
    /* ucp lang variables */
    'ML_MAILING_LIST'               => 'Mailing List',
    'ML_ALLOW_EMAILS'               => 'Sign up to the mailing list',
    'ML_PREFERENCES'                => 'Preferences',


));
?>