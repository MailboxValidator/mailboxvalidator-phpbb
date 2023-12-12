<?php
/**
 *
 * MailboxValidator Email Validator. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2023, MailboxValidator, https://mailboxvalidator.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace mailboxvalidator\emailvalidator\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * MailboxValidator Email Validator Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.user_setup'				=> 'load_language_on_setup',
			'core.user_add_modify_data'		=> 'check_newuser',
		);
	}

	/* @var \phpbb\config\config */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config		Config object
	 */
	public function __construct(\phpbb\config\config $config)
	{
		$this->config = $config;
	}

	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'mailboxvalidator/emailvalidator',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	* Query MailboxValidator Single Email Validation API and return result.
	*
	* @param string	$emailAddress	The entered email address.
	* @param string	$api_key		The MailboxValidator API Key owned by website admin.
	*/
	public function phpbb_mbv_single($emailAddress,$api_key)
	{
		try
		{
			// Now we need to send the data to MBV API Key and return back the result.
			// $url = 'https://api.mailboxvalidator.com/v1/validation/single?key=' . str_replace(' ','',$api_key) . '&email=' . str_replace(' ','',$emailAddress) . '&source=phpbb';
			$url = 'https://api.mailboxvalidator.com/v2/validation/single?key=' . str_replace(' ','',$api_key) . '&email=' . str_replace(' ','',$emailAddress) . '&source=phpbb';
			// Get the result from MBV API.
			$results = file_get_contents($url);
			if ($results != '') 
			{
				// Decode the return json results and return the data.
				$data = json_decode($results,true);
				file_put_contents ('mbv_log.log' , var_export($data, true) . PHP_EOL, FILE_APPEND);
				return $data;
			} else 
			{
				// if connection error, let it pass
				return true;
			}
		}
		catch(Exception $e) 
		{
			return true;
		}
	}

	/**
	* Extract is_valid value from MailboxValidator Single Email Validation API result.
	*
	* @param array	$api_result		Array of MailboxValidator Single Email Validation API result.
	*/
	public function phpbb_mbv_is_valid_email($api_result)
	{
		if ($api_result != '') 
		{
			if (!array_key_exists('error', $api_result)) 
			{
				if ($api_result['status']) 
				{
					return true;
				} else 
				{
					return false;
				}
			} else 
			{
				// If error message occured, let it pass first.
				return true;
			}
		} else 
		{
			// If error message occured, let it pass first.
			return true;
		}
	}
	

	/**
	* Extract is_role value from MailboxValidator Single Email Validation API result.
	*
	* @param array	$api_result		Array of MailboxValidator Single Email Validation API result.
	*/
	public function phpbb_mbv_is_role($api_result) 
	{
		if ($api_result != '') 
		{
			if (!array_key_exists('error', $api_result)) 
			{
				if ($api_result['is_role'] == 'True') 
				{
					return true;
				} else 
				{
					return false;
				}
			} else 
			{
				// If error message occured, let it pass first.
				return false;
			}
		} else 
		{
			// If error message occured, let it pass first.
			return false;
		}
	}


	/**
	* Query MailboxValidator Free Email API and return result.
	*
	* @param string	$emailAddress	The entered email address.
	* @param string	$api_key		The MailboxValidator API Key owned by website admin.
	*/
	public function phpbb_mbv_is_free($emailAddress,$api_key) 
	{
		try
		{
			// Now we need to send the data to MBV API Key and return back the result.
			$url = 'https://api.mailboxvalidator.com/v2/email/free?key=' . str_replace(' ','',$api_key) . '&email=' . str_replace(' ','',$emailAddress) . '&source=phpbb';
			// Get the result from MBV API.
			$results = file_get_contents($url);
			// Decode the return json results and return the data.
			$data = json_decode($results,true);

			if (!array_key_exists('error', $data)) 
			{
				if ($data['is_free']) 
				{
					return true;
				} else 
				{
					return false;
				}
			} else 
			{
				// If error message occured, let it pass first.
				return false;
			}
		}
		catch(Exception $e) 
		{
			return false;
		}
	}

	public function phpbb_mbv_is_disposable($emailAddress,$api_key) {
		try{
			// Now we need to send the data to MBV API Key and return back the result.
			$url = 'https://api.mailboxvalidator.com/v2/email/disposable?key=' . str_replace(' ','',$api_key) . '&email=' . str_replace(' ','',$emailAddress) . '&source=phpbb';
			// Get the result from MBV API.
			$results = file_get_contents($url);

			// Decode the return json results and return the data.
			$data = json_decode($results,true);
			
			if (!array_key_exists('error', $data)) 
			{
				if ($data['is_disposable']) 
				{
					return true;
				} else 
				{
					return false;
				}
			} else 
			{
				// If error message occured, let it pass first.
				return false;
			}
		}
		catch(Exception $e) 
		{
			return false;
		}

	}
	
	
	/**
	* Validate User email during registration
	*
	* @param array	$event		Array with event variable values
	*/
	public function check_newuser($event)
	{
		if (empty($this->config['mailboxvalidator_emailvalidator_apikey']))
		{
			return;
		}

	
			$data = $event->get_data();
			if (array_key_exists('user_row', $data) && is_array($data['user_row']) && array_key_exists('user_email', $data['user_row']))
			{
				$email = $data['user_row']['user_email'];
				// $single_result = $this->config['mailboxvalidator_emailvalidator_valid_email_on_off'] == 1 || $this->config['mailboxvalidator_emailvalidator_role_on_off'] == 1 ? phpbb_mbv_single($email, $this->config['mailboxvalidator_emailvalidator_apikey']) : '';
				$single_result = $this->config['mailboxvalidator_emailvalidator_valid_email_on_off'] == 1 ? $this->phpbb_mbv_single($email, $this->config['mailboxvalidator_emailvalidator_apikey']) : '';
				$is_valid_email = $this->config['mailboxvalidator_emailvalidator_valid_email_on_off'] == 1 && $single_result != '' ? $this->phpbb_mbv_is_valid_email($single_result) : true;
				// $is_role = $this->config['mailboxvalidator_emailvalidator_role_on_off'] == 1 && $single_result != '' ? $this->phpbb_mbv_is_role($single_result) : false;
				$is_disposable = $this->config['mailboxvalidator_emailvalidator_disposable_on_off'] == 1 ? $this->phpbb_mbv_is_disposable($email, $this->config['mailboxvalidator_emailvalidator_apikey']) : false;
				$is_free = $this->config['mailboxvalidator_emailvalidator_free_on_off'] == 1 ? $this->phpbb_mbv_is_free($email, $this->config['mailboxvalidator_emailvalidator_apikey']) : false;
				
				if($is_valid_email == false)
				{
					trigger_error('The email entered is invalid.');
				} else if ($is_disposable == true) 
				{
					trigger_error('Detected disposable email address entered.');
				} else if($is_free == true)
				{
					trigger_error('Detected free email address entered.');
				// }  else if($is_role == true)
				// {
					
				}
			}
	
		//trigger_error('Cannot register yet.');
	}
}
