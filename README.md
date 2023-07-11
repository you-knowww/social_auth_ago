CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * How it works


INTRODUCTION
------------

Social Auth ArcGIS Online is an ArcGIS Online authentication integration for
Drupal. It is based on the Social Auth and Social API projects

It adds to the site:

 * A new url: `/user/login/ago`.

 * A settings form at `/admin/config/social-api/social-auth/ago`.

 * An ArcGIS Online logo in the Social Auth Login block.


REQUIREMENTS
------------

This module requires the following modules:

 * [Social Auth](https://drupal.org/project/social_auth)
 * [Social API](https://drupal.org/project/social_api)


INSTALLATION
------------

Install as you would normally install a contributed Drupal module. See
[Installing Modules](https://www.drupal.org/docs/extending-drupal/installing-modules)
for more details.


CONFIGURATION
-------------

In Drupal:

 1. Log in as an admin.

 2. Navigate to Configuration » User authentication » Google and copy
   the Authorized redirect URL field value (the URL should end in
   `/user/login/ago/callback`).

In [ArcGIS Online](https://arcgis.com):

 3. Log in to an ArcGIS Online account.

 4. Navigate to a Web Mapping Application and select Settings.

 5. Scroll to Redirect URLs.

 6. Paste the URL copied from Step 2 in the URI field.

 7. Select Save at bottom of the page.

 8. Scroll up to Credentials.

 9. Copy Client ID and Client Secret.

In Drupal:

 10. Return to Configuration » User authentication » Google

 11. Enter the Google client ID in the Client ID field.

 12. Enter the Google secret key in the Client secret field.

 13. Click Save configuration.

 14. Navigate to Structure » Block Layout and place a Social Auth login block
     somewhere on the site (if not already placed).


HOW IT WORKS
------------

The user can click on the ArcGIS Online logo on the Social Auth Login block
You can also add a button or link anywhere on the site that points
to `/user/login/ago`, so theming and customizing the button or link
is very flexible.

After the ArcGIS Online has returned the user to your site, the module compares the user id
or email address provided by ArcGIS Online. If the user has previously registered using
ArcGIS Online or your site already has an account with the same email address, the user
is logged in. If not, a new user account is created. Also, an ArcGIS Online account can
be associated with an authenticated user.
