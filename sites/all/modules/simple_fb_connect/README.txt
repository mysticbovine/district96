SIMPLE FACEBOOK CONNECT MODULE

Simple FB Connect is a Facebook Connect Integration for Drupal.

It adds to the site:
* A new url: /user/simple-fb-connect
* A tab on /user page pointing to the above url

HOW IT WORKS
------------
User can click on the "Facebook login" tab on the user login page
You can also add a button or link anywhere on the site that points 
to /user/simple-fb-connect so theming and customizing the button or link 
is very flexible.

When the user opens the /user/simple-fb-connect link, it automatically takes 
user to Facebook for authentication. Facebook then returns the user to Drupal
site. If we have an existing Drupal user with the same email address provided
by Facebook, that user is logged in. Otherwise a new Drupal user is created.

SETUP
-----
Installation instructions for Drupal 8 can be found from the module handbook:
https://www.drupal.org/node/2642974

ADVANCED USAGE
--------------
The post login path can be defined dynamically by setting postLoginPath
query parameter.

The module also dispatches events that other modules can
subscribe to using EventSubscriber. You can also use Rules module to react
on these events.

You can easily extend Facebook integrations with a custom module.

Please refer to module handbook for more information on these topics:
https://www.drupal.org/node/2474731

SUPPORT REQUESTS
----------------
Before posting a support request, carefully read the installation
instructions provided in module handbook.

Before posting a support request, check Recent log entries at
admin/reports/dblog

Once you have done this, you can post a support request at module issue queue:
https://www.drupal.org/project/issues/simple_fb_connect

When posting a support request, please inform if you were able to see any
errors in Recent log entries.
