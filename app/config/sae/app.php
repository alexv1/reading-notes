<?php


return array(

    /*
	|--------------------------------------------------------------------------
	| SAE Wrapper Prefix
	|--------------------------------------------------------------------------
	|
	| This prefix stand for SAE wrapper. Using this prefix, we can access storage,
	| memcached, kvdb of SAE by simply keeping 'drive' of cache, session 'file'.
	|
	| Supported:
    |	        "saekv://"          (recommended for string),
    |           "saemc://"          (fastest but most expensive),
    |           "saestor://domain"  (suitable for resource)
	|
	*/

    'sae' => array(
        'wrapper' => 'saekv://',

    /*
	|--------------------------------------------------------------------------
	| SAE Storage Domain
	|--------------------------------------------------------------------------
	| User-defined string while you open Storage service at SAE control panel.
	*/

        'domain' => 'example',
    /*
	|--------------------------------------------------------------------------
	| SAE static file location
	|--------------------------------------------------------------------------
	|
	| Sae's code capacity has limit up to 100MB. So it's a good idea to put static
	| files such as images, videos on Sae storage.
	| This setting will work when mark {{SAE::style}} , {{SAE::script}} , {{SAE::image}} , {{SAE::asset}}
	| is used in blade template.
	|
	| Supported:
    |	        "storage"       (put file on Sae storage),
    |           "code"          (put file under root/public/ such as local environment),
    |
    | Url map example ("root/public/images/sample.png"): {{SAE::image('images/sample.png'}}
    |       "code":        'appname.sinaapp.com/public/images/sample.png'
    |      "storage":      'appname-domain.stor.sinaapp.com/images/sample.png'
	|
	*/

        'style'     => 'code',
        'script'    => 'code',
        'image'     => 'storage',
    ),
);