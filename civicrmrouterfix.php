<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Uri\Uri;

class PlgSystemCivicrmRouterFix extends CMSPlugin {

  public function onAfterRoute() {
    $app = Factory::getApplication();
    if (!$app->isClient('site')) {
      // We only do this on the front-end
      return;
    }

    // We only do this on GET (e.g. not on POST!)
    if ($app->input->getMethod() !== 'GET') {
        return;
    }

    // app->input has all the menu->link data we need.
    $input = $app->input;

    // Only care about CiviCRM
    if ($input->getCmd('option') !== 'com_civicrm') {
      return;
    }

    // Somehow no Itemid on this menu item? Bail.
    if (!$input->getInt('Itemid')) {
      return;
    }

    // if the path is 'index.php', we're not on a pretty url and don't need to 
    // be here. (We may actually be inside our own redirect, and we don't want
    // to loop!)
    $uri = Uri::getInstance();
    if (strpos($uri->getPath(), 'index.php') !== FALSE) {
      return;
    }
    
    $newUri = Uri::getInstance('index.php');
    foreach ($input->getArray() as $k => $v) {
        $newUri->setVar($k, $v);
    }
    $newUrl = (string) $newUri;
    $app->redirect($newUrl);
  }

}
