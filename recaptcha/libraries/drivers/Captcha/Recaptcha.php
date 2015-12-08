<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Captcha driver for "reCaptcha" style.
 *
 * $Id: Recaptcha.php 4367 2009-05-27 21:23:57Z anton.paramonov $
 *
 * @package  Captcha::reCaptcha
 * @version  2.0
 * @author   Anton Paramonov <paramonovav@gmail.com>
 * @license  https://kohanaframework.org/license
 */

class Captcha_Recaptcha_Driver extends Captcha_Driver {

    /**
     * Constructs a new challenge.
     *
     * @return  void
     */
    public function __construct()
    {
		Captcha::$config = array_merge(Kohana::config('captcha/'.Captcha::$config['style']), Kohana::config('captcha.'.Captcha::$config['group']), Captcha::$config);
    }

	public function generate_challenge()
	{
	}

	/**
	 * Fetch the Real IP Address.
	 *
	 * @return string
	 */
	public function get_real_user_ip()
	{
		// Server keys that could contain the client IP address
		$keys = array('HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');

		foreach ($keys as $key)
		{
			if (!empty($_SERVER[$key]))
			{
				return $_SERVER[$key];
			}
		}

		return '0.0.0.0';
	}

	/**
	* Validates a reCaptcha response from a user.
	*
	* @param   string   reCaptcha response
	* @return  boolean
	*/
	public function valid($response)
	{
		require_once Kohana::find_file('vendor', 'recaptchalib');

		$reCaptcha = new ReCaptcha(Captcha::$config['privateKey']);
		$responseCaptcha = $reCaptcha-> verifyResponse($this-> get_real_user_ip(), $response);

		if ($responseCaptcha == null || false == $responseCaptcha-> success)
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Outputs the reCaptcha html.
	 *
	 * @param   boolean  html output
	 * @return  mixed
	 */
	public function render($html)
	{
		return "<script src='https://www.google.com/recaptcha/api.js'></script>\r\n<div class='g-recaptcha' data-size='".Captcha::$config['size']."' data-type='".Captcha::$config['type']."' data-theme='".Captcha::$config['theme']."' data-sitekey='".Captcha::$config['siteKey']."'></div>";
	}

} // End Captcha reCaptcha Driver Class