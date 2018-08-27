<?php

namespace Drupal\rss_feed_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface as StorageDefinition;

/**
 * Plugin implementation of the 'RSS feed' field type.
 *
 * @FieldType(
 *   id = "rss_feed_field",
 *   label = @Translation("RSS feed URL"),
 *   description = @Translation("Displays RSS feed"),
 *   category = @Translation("Custom"),
 *   default_widget = "rss_feed_field",
 *   default_formatter = "rss_feed_field"
 * )
 */
class RssFeed extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(StorageDefinition $storage) {

    $properties = [];
    $properties['url'] = DataDefinition::create('string')
      ->setLabel(t('URL'));

    $properties['count'] = DataDefinition::create('integer')
      ->setLabel(t('Count'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   *
   * List of allowed column types at https://goo.gl/YY3G7s.
   */
  public static function schema(StorageDefinition $storage) {

    $columns = [];
    $columns['url'] = [
      'type' => 'varchar',
      'length' => 255,
    ];
    $columns['count'] = [
      'type' => 'int',
      'length' => 2,
    ];

    return [
      'columns' => $columns,
      'indexes' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $isEmpty = empty($this->get('url')->getValue());

    return $isEmpty;
  }

}
