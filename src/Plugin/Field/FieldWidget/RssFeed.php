<?php

namespace Drupal\rss_feed_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Field widget for rss fields.
 *
 * @FieldWidget(
 *   id = "rss_feed_field",
 *   label = @Translation("RSS Feed"),
 *   field_types = {
 *     "rss_feed_field"
 *   }
 * )
 */
class RssFeed extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $formState) {

    $element['url'] = [
      '#type' => 'textfield',
      '#title' => t('RSS feed URL'),
      '#default_value' => isset($items[$delta]->url) ? $items[$delta]->url : NULL,
      '#empty_value' => '',
      '#placeholder' => t('Fully qualified RSS feed URL'),
    ];
    $element['count'] = [
      '#type' => 'select',
      '#title' => t('Number of feed items to display.'),
      '#default_value' => isset($items[$delta]->count) ? $items[$delta]->count : NULL,
      '#options' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'count' => 3,
    ] + parent::defaultSettings();
  }

}
