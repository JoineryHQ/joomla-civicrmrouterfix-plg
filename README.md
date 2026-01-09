# CiviCRM Router Fix (Joomla 4)

## Purpose

Work around a Joomla 4 routing bug where menu items pointing to CiviCRM
drop required query parameters (e.g. contribution page id), causing
"Could not find a valid session key" errors.

The nature and cause of this problem is not fully understood, but severl
reports of such problems have been made, for example:
- https://lab.civicrm.org/dev/joomla/-/issues/48 
- https://lab.civicrm.org/dev/joomla/-/issues/49
- https://civicrm.stackexchange.com/q/18904/907

## What it does

- Runs on the site front-end only
- Applies only to com_civicrm menu items
- Detects "pretty" menu URLs (no index.php)
- Redirects once to the equivalent index.php URL with full query params
  (so CiviCRM knows what's going on!)
- Uses index.php as a loop breaker to prevent endless redirects.

## Result

Pretty menu URL:
- example.com/membership/active-membership (which has been built by
  Joomla based on menu item parent/alias)

Becomes:
- example.com/index.php?option=com_civicrm&task=civicrm/contribute/transact&id=2&Itemid=359
  (Which is a full expression of the menu item's "Link" attribute)

This ensures Joomla and CiviCRM compute session keys correctly, and that
CiviCRM has access to the query parameters it needs.

Notes
-----
- Redirects GET requests only
- Does not incur database query costs
- Does not modify .htaccess (nor require the site admin to do so)
- Safe to enable site-wide

Install
-------
Copy plugin files to:
  [joomla-root]/plugins/system/civicrmrouterfix/

Then:
  Admin → System → Discover → Install
  Admin → Plugins → System → CiviCRM Router Fix → Enable


## Support
Support for this plugin is handled under Joinery's ["As-Is Support" policy](https://joineryhq.com/software-support-levels#as-is-support).

Public issue queue for this plugin: https://github.com/JoineryHQ/uptime-pushover/issues