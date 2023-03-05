Node read time

INTRODUCTION
------------
Reading time is a module that provides an extra field for content types,
which displays to the users the time it will take for them to read a node.
This field takes into consideration all the textfields part of the content type,
plus entity reference revision fields like Paragraphs, Custom blocks etc.
It also comes with a configuration page, where you can:
- activate the reading time field for specific content types;
- set the "words per minute" value, which is part of the
calculation of the reading time;

Also, the module provides twig template, which can be easily modified
in your custom theme.

REQUIREMENTS
------------
No other modules required.


INSTALLATION
------------
 * Install as you would normally install a contributed drupal module. See:
   https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules
   for further information.


CONFIGURATION
-------------
You can configure the module from /admin/config/reading-time

_NOTE: If you do not set words per minute, a default
value of 225 will be considered as part of the calculation._
