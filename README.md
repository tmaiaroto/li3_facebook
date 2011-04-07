---
title: li3_facebook : a Lithium Facebook Library Wrapper
subtitle: Integration Facebook into your Lithium Framework
author: weluse GmbH : Marc Schwering

---

Lithium the most RAD PHP Framework Facebook Wrapper
===================================================

This Wrapper is a Lithium PHP Library using the official Facebook PHP SDK.

Usage
-----

This Library is using the official facebook api as a git submodule!

So : Please don't forget to do a git submodule init!

You will need a Facebook API-Key and the Lithium Framework.
Integrate the Library in the bootstrap process of your lovely li3 App:

Libraries::add('li3_facebook', array(
	'appId' => key
	'secret' => yourSuperSecretKey
));

these official Facebook SDK Settings are currently not yet supported.
 - cookie
 - domain
 - fileUpload

if you want to enable those Settings, you have to unset the validation:

li3_facebook\extension\FacebookProxy::$_validateConfiguration = false;

After Configuration you should able to use it via static calls or direkt method invoking:

static:
li3_facebook\extension\FacebookProxy::getAppId($params)

or
li3_facebook\extension\FacebookProxy::run('getAppId',$params)