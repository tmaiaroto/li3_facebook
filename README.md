---
title: li3_translate : a Lithium Translatable Behavior
subtitle: Translatable content for your Lithium Application
author: Richard McIntyre - @mackstar

---

Lithium the most RAD PHP Framework Translatable Behavior
========================================================

This plugin is easiest to use when used in combination with the li3_behaviors plugin.

What this behavior does is enable you to have content of different locales/languages to be stored in your MongoDB database via your lithium based model. You can also search and retrieve locale specific data simply. 

* At this moment the plugin is only compatible with MongoDB.

If somebody wanted to make it adaptable then other data sources could be supported in the future.

Usage
-----

Please install li3_behaviors first then add the following to your libraries.php or where ever else you add your lithium libraries.

```
Libraries::add('li3_translate');
Libraries::add('li3_behaviors');
````

Then in the model you wish to have translatable please add something to the tune of:

```
protected $_actsAs = array(
	'Translatable' => array(
		'validation' => 'ja',
		'locales' => array('en', 'it', 'ja'),
		'fields' => array('name', 'profile')
	)
);
```

The validation option is only necessary if you are saving multiple languages in one create or save command. A base language of which to gather the content and validate against is needed. This ensures that your validations will still work.

The locales that you want to use is fairly self explanatory, it simply tells the plugin which languages you want support for.

So as not to double up on too much data. The fields array tells the behavior which fields will need localizations. Those that are not included here will be simple fields which will not be attached a locale.

Good example usage of the plugin can be seen in the unit tests, but here is a brief description.

Saving data
-----------

When saving data you can save it in the normal manner, if you are saving a single locale you can do so by

```
$user = Users::create(array('name'=>'Richard', 'profile'=>'Dreaded Rasta', 'locale' => 'en'));
$user->save();
```

If you would like to search for this record you can do so by either using a locale condition or a locale option. I will explain the difference.

Locale Condition
----------------

An example of using the condition is as below. It will successfully find the record saved above.

```
$user = Users::first(array('conditions' => array('name' => 'Richard', 'locale' => 'en')));
```

The record condition parameter has been set but the locale option has not been set so the query will return all available languages for us to use. You can retrieve the name below:

```$user->en->name```

But should their already exist for example a Japanese translation with the locale set to ja, you can get the Japanese name as follows:

```$user->ja->name```

Locale Option
-------------

The locale option will return the set local record only. It will also search that locale only.

```$user = Users::first(array('conditions' => array('name' => 'Richard'), 'locale' => 'en'));```

To retrieve the data it is as normal

```$user->name```

Saving Locales Later
--------------------

It is very easy to add a locale to an existing record, either of the following will work.

```
$user = Users::first(array('conditions' => array('name' => 'Richard', 'locale' => 'en')));
$user->save(array('locale' => 'it', 'name' => 'Ricardo'));
```

Or of course by assigning the properties

```
$user = Users::first(array('conditions' => array('name' => 'Richard', 'locale' => 'en')));
$user->locale = 'it';
$user->name = 'Ricardo';
```

Saving More Than One Locale At A Time
-------------------------------------

This can be done as simply as:

```
$user = Users::create(array(
	'ja.name'=>'リチャード', 
	'en.name'=>'Richard', 
	'it.name'=>'Ricardo', 
	'non_localized_field' => 'Here is something interesting.'
));
$artist->save();
```
* In order to use this saving style a validation locale key is needed in the configuration.

Other ways to find
------------------

You can also use the convenient style below to find content:

```$users = Users::all(array('conditions' => array('it.name' => 'Ricardo')));```

If you do not know the translation you are searching for, the locales are kept in the reserved key `locales` field and can be searched by the following:

```$users = Users::all(array('conditions' => array('locales.name' => 'Ricardo')));```

Both of these will of course return all locales.

Bugs etc
--------

I have yet tested this plugin for white lists and other features. If you find a case that doesn't work then please log an issue.
