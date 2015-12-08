<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package  Captcha::reCaptcha
 * @version  2.0
 * @author   Anton Paramonov <paramonovav@gmail.com>
 * @license  https://kohanaframework.org/license
 *
 * reCaptcha configuration (https://www.google.com/recaptcha/intro/index.html)
 *
 * Options:
 *  theme      - reCaptcha theme, e.g. dark, light
 *  type       - reCaptcha type, e.g. audio, image
 *  size       - reCaptcha size, e.g. compact, normal
 *  siteKey    - reCaptcha sitekey for html (see more on https://developers.google.com/recaptcha/docs/display)
 *  privateKey - reCaptcha secretkey for verify (see more on https://developers.google.com/recaptcha/docs/verify)
 */
$config = array
(
    'theme'      => 'light',
    'type'       => 'image',
    'size'       => 'normal',
    'siteKey'    => '',
    'privateKey' => ''
);