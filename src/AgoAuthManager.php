<?php

namespace Drupal\social_auth_ago;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\social_auth\AuthManager\OAuth2Manager;
use Drupal\social_auth\User\SocialAuthUser;
use Drupal\social_auth\User\SocialAuthUserInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Contains all the logic for AGO OAuth2 authentication.
 */
class AgoAuthManager extends OAuth2Manager {

  /**
   * AgoAuthManager constructor.
   */
  public function __construct(ConfigFactory $configFactory,
                              LoggerChannelFactoryInterface $logger_factory,
                              RequestStack $request_stack) {

    parent::__construct($configFactory->get('social_auth_ago.settings'),
                        $logger_factory,
                        $request_stack->getCurrentRequest());
  }

  /**
   * {@inheritdoc}
   */
  public function authenticate(): void {
    try {
      $this->setAccessToken($this->client->getAccessToken('authorization_code',
        ['code' => $this->request->query->get('code')]));
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
      $this->user->setFirstName($owner_array['firstName']);
      $this->user->setLastName($owner_array['lastName']);
    }
    return $this->user;
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthorizationUrl(): string {
    $scopes = [
      'email',
      'profile',
    ];

    $extra_scopes = $this->getScopes();
    if ($extra_scopes) {
      $scopes = array_merge($scopes, explode(',', $extra_scopes));
    }

    // Returns the URL where user will be redirected.
    return $this->client->getAuthorizationUrl([
      'scope' => $scopes,
    ]);
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
