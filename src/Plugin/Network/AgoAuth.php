<?php

namespace Drupal\social_auth_ago\Plugin\Network;

use Drupal\social_auth\Plugin\Network\NetworkBase;
use Drupal\social_auth\Plugin\Network\NetworkInterface;

/**
 * Defines a Network Plugin for Social Auth ArcGIS Online.
 *
 * @package Drupal\social_auth_ago\Plugin\Network
 *
 * @Network(
 *   id = "social_auth_ago",
 *   short_name = "ago",
 *   social_network = "ArcGIS Online",
 *   img_path = "img/ago_logo.svg",
 *   type = "social_auth",
 *   class_name = "\League\OAuth2\Client\Provider\GenericProvider",
 *   auth_manager = "\Drupal\social_auth_ago\AgoAuthManager",
 *   routes = {
 *     "redirect": "social_auth.network.redirect",
 *     "callback": "social_auth.network.callback",
 *     "settings_form": "social_auth.network.settings_form",
 *   },
 *   handlers = {
 *     "settings": {
 *       "class": "\Drupal\social_auth_ago\Settings\AgoAuthSettings",
 *       "config_id": "social_auth_ago.settings"
 *     }
 *   }
 * )
 */
class AgoAuth extends NetworkBase {
  /**
   * Gets additional settings for the network class.
   *
   * Implementors can declare and use this method to augment the settings array
   * passed to constructors for libraries that extend from a League abstract
   * provider. urlAuthorize and urlAccessToken necessary for generic provider
   *
   * @return array
   *   Key-value pairs for extra settings to pass to the provider class
   *   constructor.
   *
   * @see \Drupal\social_auth\Plugin\Network\NetworkBase::initSdk()
   */
  protected function getExtraSdkSettings(): array {
    return ['urlAuthorize' => $this->settings->getAuthorizationUrl(),
            'urlAccessToken' => $this->settings->getAccessTokenUrl(),
            'urlResourceOwnerDetails' => $this->settings->getResourceOwnerDetailsUrl()];
  }

}
