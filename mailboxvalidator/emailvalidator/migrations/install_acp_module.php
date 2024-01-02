<?php
/**
 *
 * MailboxValidator Email Validator. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2024, MailboxValidator, https://mailboxvalidator.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace mailboxvalidator\emailvalidator\migrations;

class install_acp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['mailboxvalidator_emailvalidator_apikey']);
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('mailboxvalidator_emailvalidator_apikey', '')),
			array('config.add', array('mailboxvalidator_emailvalidator_valid_email_on_off', 0)),
			array('config.add', array('mailboxvalidator_emailvalidator_disposable_on_off', 0)),
			array('config.add', array('mailboxvalidator_emailvalidator_free_on_off', 0)),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_EMAILVALIDATOR_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_EMAILVALIDATOR_TITLE',
				array(
					'module_basename'	=> '\mailboxvalidator\emailvalidator\acp\main_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
