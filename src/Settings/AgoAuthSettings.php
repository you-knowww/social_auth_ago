<?php

namespace Drupal\social_auth_ago\Settings;

use Drupal\social_auth\Settings\SettingsBase;

/**
 * Defines methods to get Social Auth ArcGIS Online settings.
 */
class AgoAuthSettings extends SettingsBase implements AgoAuthSettingsInterface {

  /**
   * Authorization Url.
   *
   * @var string|null
   */
  protected ?string $authorizationUrl = NULL;

  /**
   * {@inheritdoc}
   */
  public function getAuthorizationUrl(): ?string {
    if (!$this->authorizationUrl) {
      $this->authorizationUrl = $this->config->get('url_authorization');
    }
    return $this->authorizationUrl;
  }

  /**
   * Access Token Url.
   *
   * @var string|null
   */
  protected ?string $accessTokenUrl = NULL;

  /**
   * {@inheritdoc}
   */
  public function getAccessTokenUrl(): ?string {
    if (!$this->accessTokenUrl) {
      $this->accessTokenUrl = $this->config->get('url_access_token');
    }
    return $this->accessTokenUrl;
  }

  /**
   * Resource Owner Detail Url to get user info.
   *
   * @var string|null
   */
  protected ?string $resourceOwnerDetailsUrl = NULL;

  /**
   * {@inheritdoc}
   */
  public function getResourceOwnerDetailsUrl(): ?string {
    if (!$this->resourceOwnerDetailsUrl) {
      $this->resourceOwnerDetailsUrl = $this->config->get('url_resource_owner_details');
    }
    return $this->resourceOwnerDetailsUrl;
  }
}
