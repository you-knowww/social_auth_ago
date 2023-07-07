<?php

namespace Drupal\social_auth_ago\Settings;

use Drupal\social_auth\Settings\SettingsInterface;

/**
 * Defines an interface for Social Auth ArcGIS Online settings.
 */
interface AgoAuthSettingsInterface extends SettingsInterface {

  /**
   * Gets Authorization URL.
   *
   * @return string|null
   *   The Authorization URL.
   */
  public function getAuthorizationUrl(): ?string;

  /**
   * Gets Access Token Url.
   *
   * @return string|null
   *   The Access Token URL.
   */
  public function getAccessTokenUrl(): ?string;

  /**
   * Gets Resource Owner Details URL for user info.
   *
   * @return string|null
   *   The Resource Owner Details URL.
   */
  public function getResourceOwnerDetailsUrl(): ?string;
}
