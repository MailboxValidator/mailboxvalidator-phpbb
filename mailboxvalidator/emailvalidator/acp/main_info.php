<?php
/**
 *
 * MailboxValidator Email Validator. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, MailboxValidator, https://mailboxvalidator.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace mailboxvalidator\emailvalidator\acp;

/**
 * MailboxValidator Email Validator ACP module info.
 */
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\mailboxvalidator\emailvalidator\acp\main_module',
			'title'		=> 'ACP_EMAILVALIDATOR_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_EMAILVALIDATOR',
					'auth'	=> 'ext_mailboxvalidator/emailvalidator && acl_a_board',
					'cat'	=> array('ACP_EMAILVALIDATOR_TITLE')
				),
			),
		);
	}
}
