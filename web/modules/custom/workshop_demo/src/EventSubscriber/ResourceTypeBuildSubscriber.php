<?php declare(strict_types=1);

namespace Drupal\workshop_demo\EventSubscriber;

use Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent;
use Drupal\jsonapi\ResourceType\ResourceTypeBuildEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ResourceTypeBuildSubscriber implements EventSubscriberInterface {

  public function disableResourceType(ResourceTypeBuildEvent $event) {
    [$entity_type_id, ] = explode('--', $event->getResourceTypeName());
    $disabled_entity_types = [
      'action',
      'entity_form_mode',
      'entity_form_display',
      'entity_view_display',
      'entity_view_mode',
    ];
    if (in_array($entity_type_id, $disabled_entity_types, TRUE)) {
      $event->disableResourceType();
    }
  }

  public function aliasResourceTypeFields(ResourceTypeBuildEvent $event) {
    [$entity_type_id, $bundle] = explode('--', $event->getResourceTypeName());
    foreach ($event->getFields() as $field) {
      if ($entity_type_id === 'node') {
        // Do specifics.
        // strpos($entity_type_id, 'commerce_') === 0
      }
      // Disable the internal Drupal identifiers.
      if (strpos($field->getPublicName(), 'drupal_internal__') === 0) {
        $event->disableField($field);
      }
      if ($field->getInternalName() === 'default_langcode') {
        $event->disableField($field);
      }
      // Change {entity_type_id}_type bundle key to just bundle.
      elseif ($field->getPublicName() === $entity_type_id . '_type') {
        $event->setPublicFieldName($field, 'bundle');
      }
      // Rename `mail` to email.
      elseif ($field->getInternalName() === 'mail') {
        $event->setPublicFieldName($field, 'email');
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ResourceTypeBuildEvents::BUILD => [
        ['disableResourceType'],
        ['aliasResourceTypeFields'],
      ],
    ];
  }

}
