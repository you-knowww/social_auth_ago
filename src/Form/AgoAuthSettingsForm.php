<?php

namespace Drupal\social_auth_ago\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\social_auth\Form\SocialAuthSettingsForm;
use Drupal\social_auth\Plugin\Network\NetworkInterface;

/**
 * Settings form for Social Auth Ago.
 */
class AgoAuthSettingsForm extends SocialAuthSettingsForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'social_auth_ago_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return array_merge(
      parent::getEditableConfigNames(),
      ['social_auth_ago.settings']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?NetworkInterface $network = NULL): array {
    /** @var \Drupal\social_auth\Plugin\Network\NetworkInterface $network */
    $network = $this->networkManager->createInstance('social_auth_ago');
    $form = parent::buildForm($form, $form_state, $network);

    $config = $this->config('social_auth_ago.settings');

    $form['network']['url_base'] = [
      '#type' => 'textfield',
      '#disabled' => FALSE,
      '#title' => $this->t('Base URL'),
      '#description' => $this->t('URL Base for ArcGIS Online or Portal. Default for AGO is <em>https://www.arcgis.com</em>. Portal usually ends in /portal, for example <em>https://www.fake-portal.com/portal</em>.'),
      '#default_value' => 'https://www.arcgis.com',
    ];

    $form['network']['url_authorization'] = [
      '#type' => 'textfield',
      '#disabled' => FALSE,
      '#title' => $this->t('Authorizion URL Endpoint'),
      '#description' => $this->t('URL Endpoint for ArcGIS Online or Portal authorization. Default for AGO is <em>https://www.arcgis.com/sharing/rest/oauth2/authorize</em>.'),
      '#default_value' => 'https://www.arcgis.com/sharing/rest/oauth2/authorize',
    ];

    $form['network']['url_access_token'] = [
      '#type' => 'textfield',
      '#disabled' => FALSE,
      '#title' => $this->t('Token URL Endpoint'),
      '#description' => $this->t('URL Endpoint for ArcGIS Online or Portal tokens. Default for AGO is <em>https://www.arcgis.com/sharing/rest/oauth2/token</em>.'),
      '#default_value' => 'https://www.arcgis.com/sharing/rest/oauth2/token',
    ];

    $form['network']['url_resource_owner_details'] = [
      '#type' => 'textfield',
      '#disabled' => FALSE,
      '#title' => $this->t('Resource Owner Details URL Endpoint'),
      '#description' => $this->t('URL Endpoint for ArcGIS Online or Portal resource owner details. Default for AGO is <em>https://www.arcgis.com/sharing/rest/community/self?f=json</em>.'),
      '#default_value' => 'https://www.arcgis.com/sharing/rest/community/self?f=json',
    ];

    $form['network']['authorized_javascript_origin'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Authorized Javascript Origin'),
      '#description' => $this->t('Copy this value to <em>Allowed Origins</em> field of your ArcGIS Online App settings.'),
      '#default_value' => $GLOBALS['base_url'],
    ];

    $form['network']['advanced']['scopes']['#description'] =
      $this->t('Define any additional scopes to be requested, separated by a comma.<br>
        The scopes for \'email\' and \'username\' are added by default and always requested.');

    $form['network']['advanced']['endpoints']['#description'] =
       $this->t('Define the Endpoints to be requested when user authenticates with Ago for the first time<br>
        Enter each endpoint in different lines in the format <em>endpoint</em>|<em>name_of_endpoint</em>.<br><b>For instance:</b><br>
        https://www.arcgis.com/sharing/rest/community/self?f=json<br>'
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $values = $form_state->getValues();
    $this->config('social_auth_ago.settings')
      ->set('client_id', trim($values['client_id']))
      ->set('client_secret', trim($values['client_secret']))
      ->set('url_base', trim($values['url_base']))
      ->set('url_authorization', trim($values['url_authorization']))
      ->set('url_access_token', trim($values['url_access_token']))
      ->set('url_resource_owner_details', trim($values['url_resource_owner_details']))
      ->set('scopes', $values['scopes'])
      ->set('endpoints', $values['endpoints'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
