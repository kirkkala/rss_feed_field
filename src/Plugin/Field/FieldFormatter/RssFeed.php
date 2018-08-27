<?php

namespace Drupal\rss_feed_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Field formatter for rss feeds.
 *
 * @FieldFormatter(
 *   id = "rss_feed_field",
 *   label = @Translation("RSS Feed"),
 *   field_types = {
 *     "rss_feed_field"
 *   }
 * )
 */
class RssFeed extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $elements['show_title'] = [
      '#title' => $this->t('Show title'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('show_title'),
    ];

    $elements['items'] = [
      '#title' => $this->t('Items'),
      '#type' => 'textfield',
      '#default_value' => $this->getSetting('items'),
      '#description' => $this->t('Number of items to display. Set <em>0</em> to display all items.'),
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'items' => 5,
      'show_title' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $amount = $this->getSetting('items') == 0 ? $this->t('all') : $this->getSetting('items');
    $summary = [];
    $summary[] = $this->getSetting('show_title') ? $this->t('Show title') : $this->t('Hide title');
    $summary[] = $this->t('Display @amount items', ['@amount' => $amount]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $feed_items = [];

    // Loop the field instances.
    foreach ($items as $delta => $item) {

      $uri = $item->url;
      $feed = simplexml_load_file($uri);

      if ($items->count != 0) {
        $count = $items->count;
      }
      else {
        // @todo: class refactoring and use of use statement.
        $count = \Drupal\rss_feed_field\Plugin\Field\FieldFormatter\RssFeed::defaultSettings()['items'];
      }

      // Build array from SimpleXMLElements.
      for ($i = 0; $i < $count; $i++) {
        $feed_item = $feed->channel->item;

        // Stop looping once the last item is hit.
        if ($feed_item[$i] === NULL) {
          break;
        }

        $pub_date = $feed_item[$i]->pubDate;
        $pub_date = \Drupal::service('date.formatter')->format(strtotime($pub_date), 'short');

        $feed_items[] = [
          'title' => $feed_item[$i]->title,
          'description' => $feed_item[$i]->description,
          'link' => $feed_item[$i]->link,
          'pubDate' => $pub_date,
        ];
      }

      $feed_title = $feed->channel->title;

      $element = [
        '#theme' => 'rss_feed_field',
        '#title' => $this->getSetting('show_title') ? $feed_title : NULL,
        '#items' => $feed_items,
      ];

      $elements[$delta] = $element;
    }

    return $elements;
  }

}
