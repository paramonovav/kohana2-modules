## Kohana 2.x modules

### reCAPTCHA

#### Installation and Configuration

1. Copy "recaptcha" folder to your project/modules folder.
2. Copy folder modules/recaptcha/config/captcha to application/config.
3. Enable this module in application/config/config.php.
```
/**
 * Additional resource paths, or "modules". Each path can either be absolute
 * or relative to the docroot. Modules can include any resource that can exist
 * in your application directory, configuration files, controllers, views, etc.
 */
$config['modules'] = array
(
    ...
    MODPATH.'recaptcha',
    ...
);
```

Get siteKey and privateKey keys on [Google reCAPTCHA](https://www.google.com/recaptcha/intro/index.html) and change config file application/config/captcha/recaptcha.php

```
/*
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
```

Configuration is done in the application/config/captcha.php file, if it's not there take the one from system/config and copy it to the application folder.

```
$config['default'] = array
(
    'style'      => 'recaptcha',
    'promote'    => FALSE,
    //you can override option from application/config/captcha/recaptcha.php
    'theme'      => 'dark',
    'type'       => 'image',
    'size'       => 'normal',
    //also you can change/override standart recaptcha field name from "g-recaptcha-response"
    'field'      => 'code',
);
```

#### Usage example
> The code below demonstrates how to use captcha on a form. In your controller:

```
class Example_Controller extends Controller
{
    public function index()
    {
        $this-> Captcha = Captcha::instance();
          
        if (empty($_POST))
        {
          return;
        }
          
        $this-> Form = Validation::factory($_POST);
        $this-> Form-> pre_filter('trim');
        $this-> Form-> add_rules('g-recaptcha-response', 'required', 'Captcha::valid');
        # or 
        #$this-> Form-> add_rules('code', 'required', 'Captcha::valid');

        if (!$this-> Form-> validate())
        {
          print_r($this-> Form-> errors('errors'));
          return;
        }
    }
}
```

### ExtCache

#### Installation and Configuration

1. Copy "extcache" folder to your project/modules folder.
2. Copy folder modules/extcache/config/cache to application/config/cache.
3. Enable this module in application/config/config.php

```
/**
 * Additional resource paths, or "modules". Each path can either be absolute
 * or relative to the docroot. Modules can include any resource that can exist
 * in your application directory, configuration files, controllers, views, etc.
 */
$config['modules'] = array
(
    ...
    MODPATH.'extcache',
    ...
);
```

4. This module provides several cache drivers: 
    * Memcached (need install php-memcached extension). Config application/config/cache/memcached.php.
    * Igbinaryfile (need install php-igbinary)
    * Jsonfile
    * Msgpackfile (need install php-msgpack)
    * PHP (some reasons some times need for developers)
