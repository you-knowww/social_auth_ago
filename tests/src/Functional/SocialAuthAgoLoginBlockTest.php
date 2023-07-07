<?php

namespace Drupal\Tests\social_auth_ago\Functional;

use Drupal\Tests\social_auth\Functional\SocialAuthTestBase;

/**
 * Test that path to authentication route exists in Social Auth Login block.
 *
 * @group social_auth_ago
 */
class SocialAuthAgoLoginBlockTest extends SocialAuthTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['block', 'social_auth_ago'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->provider = 'ago';
  }

  /**
   * Test that the path is included in the login block.
   */
  public function testLinkExistsInBlock() {
    $this->checkLinkToProviderExists();
  }

}
