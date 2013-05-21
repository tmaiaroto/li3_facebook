# li3_facebook

This Wrapper is a Lithium PHP Library using the official Facebook PHP SDK.
Thanks goes out to [tmaiaroto](https://github.com/tmaiaroto) for writing the original.
This fork builds on his work to provide [Composer](http://getcomposer.org/) support and to remove all `.gitmodules`.

## Installation

> *Note: You will need a Facebook API key/secret.

Load `li3_facebook` by updating `config/bootstrap/libraries.php`:

```php
<?php

// ... snip ...

Libraries::add('li3_facebook', array(
    'appId'  => '',
    'secret' => '',
));
```

## To-Do

These official Facebook SDK Settings are currently not yet supported:

* cookie
* domain
* fileUpload

if you want to enable those Settings, you have to unset the validation:

li3_facebook\extension\FacebookProxy::$_validateConfiguration = false;

After Configuration you should able to use it via static calls or direkt method invoking:

static:
li3_facebook\extension\FacebookProxy::getAppId($params)

or
li3_facebook\extension\FacebookProxy::run('getAppId',$params)
