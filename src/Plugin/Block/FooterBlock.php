<?php

namespace Drupal\kififooter\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Render a menu in a block
 *
 * @Block(
 *   id = "kififooter_block",
 *   admin_label = @Translation("Common Footer")
 * )
 */
class FooterBlock extends BlockBase {
  const BASE_URL = 'https://gfx.kirjastot.fi/shared-footer/';

  public function build() {
    $data = self::fetchFooterData();

    return [
      '#theme' => 'kififooter',
      '#data' => $data,
      '#attached' => [
        'library' => ['kififooter/kififooter']
      ]
    ];
  }

  public static function fetchFooterData() {
    $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $response = \Drupal::httpClient()->get(sprintf('%s?lang=%s', self::BASE_URL, $langcode), [
      'headers' => [
        'X-Requested-With' => 'XMLHttpRequest'
      ]
    ]);

    $data = json_decode($response->getBody(), TRUE);
    $data['base_url'] = self::BASE_URL;
    
    return $data;
  }
}
