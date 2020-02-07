<?php
/**
 *
 * MailboxValidator Email Validator. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, MailboxValidator, https://mailboxvalidator.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

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
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(

	// 'EMAILVALIDATOR_HELLO'		=> 'Hello %s!',
	// 'EMAILVALIDATOR_GOODBYE'		=> 'Goodbye %s!',

	'EMAILVALIDATOR_EVENT'		=> ' :: Emailvalidator Event :: ',
	
	'ACP_MBV_SETTINGS_APIKEY_LABEL'	=> 'API Key',
	'ACP_MBV_SETTINGS_APIKEY_DESC'	=> 'Please enter the API key got from ',
	
	'ACP_MBV_VALID_EMAIL'	=> 'Valid Email Validator',
	'ACP_MBV_DISPOSABLE_EMAIL'	=> 'Disposable Email Validator',
	'ACP_MBV_FREE_EMAIL'	=> 'Free Email Validator',

));
