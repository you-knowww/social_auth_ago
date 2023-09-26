<?php

namespace Drupal\social_auth_ago;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\social_auth\AuthManager\OAuth2Manager;
use Drupal\social_auth\User\SocialAuthUser;
use Drupal\social_auth\User\SocialAuthUserInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\social_auth_ago\Settings\AgoAuthSettings;

/**
 * Contains all the logic for AGO OAuth2 authentication.
 */
class AgoAuthManager extends OAuth2Manager {
  private $config_settings;

  /**
   * AgoAuthManager constructor.
   */
  public function __construct(ConfigFactoryInterface $config_factory,
                              LoggerChannelFactoryInterface $logger_factory,
                              RequestStack $request_stack) {

    // print_r($configFactory->get('social_auth_ago.settings'));
    $this->config_settings = $config_factory->get('social_auth_ago.settings');
    parent::__construct($this->config_settings,
                        $logger_factory,
                        $request_stack->getCurrentRequest());
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate(): void {
    try {
      $token = $this->client->getAccessToken(
                    'authorization_code',
                    ['code' => $this->request->query->get('code')]);
      $this->setAccessToken($token);

      // store to session for easy retrieval
      $request = \Drupal::request();
      $session = $request->getSession();

      // add reference to auth url for frontend
      $session->set('ago_access_token',
          (object) array('token' => $token,
            'url' => $this->settings->get('url_base'),
            'client_id' => $this->settings->get('client_id')));
    }
    catch (IdentityProviderException $e) {
      $this->loggerFactory->get('social_auth_ago')
        ->error('There was an error during authentication. Exception: ' . $e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getUserInfo(): SocialAuthUserInterface {
    if (!$this->user) {
      /** @var \League\OAuth2\Client\Provider\GenericResourceOwner $owner */
      $owner = $this->client->getResourceOwner($this->getAccessToken());

      // owner response array to simplify user setup
      $owner_array = $owner->toArray();

      // create social auth user with only AGO uname and email
      $this->user = new SocialAuthUser(
        $owner_array['username'],
        $owner->getId(),
        $this->getAccessToken(),
        $owner_array['email']
      );
      // set first and last
      //$this->user->setFirstName($owner_array['firstName']);
      //$this->user->setLastName($owner_array['lastName']);
    }
    return $this->user;
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthorizationUrl(): string {
    $scopes = $this->getScopes();

    // Returns the URL where user will be redirected.
    return $this->client->getAuthorizationUrl(['scope' => $scopes]);
  }

  /**
   * {@inheritdoc}
   */
  public function requestEndPoint(string $method, string $path, ?string $domain = NULL, array $options = []): mixed {
    if (!$domain) {
      $domain = 'https://www.arcgis.com/sharing/rest/community';
    }

    $url = $domain . $path;

    $request = $this->client->getAuthenticatedRequest($method, $url, $this->getAccessToken(), $options);

    try {
      return $this->client->getParsedResponse($request);
    }
    catch (IdentityProviderException $e) {
      $this->loggerFactory->get('social_auth_ago')
        ->error('There was an error when requesting ' . $url . '. Exception: ' . $e->getMessage());
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getState(): string {
    return $this->client->getState();
  }
}
