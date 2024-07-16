<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// To use reCAPTCHA, you need to sign up for an API key pair for your site.
// link: http://www.google.com/recaptcha/admin
// Using email gmail for API
//puskom pcr $config['recaptcha_site_key'] = '6LfB-WoUAAAAACtOh3fyyG2_KmEeQn5J5S8GDU8E';
//$config['recaptcha_secret_key'] = '6LfB-WoUAAAAAMJut3SA1c3ZG6Ti1plgswI1T7UM';

//localhost for v2
/*$config['recaptcha_site_key'] = '6Ld9PqUUAAAAAI0FhW7MBhbijUZzkuVZrQD1RVaM';
$config['recaptcha_secret_key'] = '6Ld9PqUUAAAAALwk9x5OlLYCeI_wgv81gCqAqkDy';*/

//for localhost v3
$config['recaptcha_site_key'] = '6Le0bzwaAAAAAOa5HnNo0hbA2Qqd02nvayYmiO7K';
$config['recaptcha_secret_key'] = '6Le0bzwaAAAAAOpjPSjIR9wIgNYmWC2lB51tRvsB';

//production for v3
/*$config['recaptcha_site_key'] = '6LcpaTwaAAAAAELpDtspI8qC0R6b91uPSAIdov5J';
$config['recaptcha_secret_key'] = '6LcpaTwaAAAAAJ3LUGgnaFgYoiyqzOUKa13ouEYo';*/

// reCAPTCHA supported 40+ languages listed here:
// https://developers.google.com/recaptcha/docs/language
$config['recaptcha_lang'] = 'en';

/* End of file recaptcha.php */
/* Location: ./application/config/recaptcha.php */
