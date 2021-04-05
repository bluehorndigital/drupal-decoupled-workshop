# Decoupled Drupal Workshop

## Notes

### Required patches

Unfortunately, things don't always work well out of the box. Review `composer.patches.json` to see what was required.

#### Decoupled Router

(Cannot generate the entity canonical url by passing the entity UUID
)[https://www.drupal.org/project/decoupled_router/issues/3116487]

#### Consumer Image Styles

(Make JSON:API Extras an optional dependency)[https://www.drupal.org/project/consumer_image_styles/issues/3171903]
(JsonApiResource Link $rel is now a string, not an array; getLinkRelationTypes() is now getLinkRelationType())[https://www.drupal.org/project/consumer_image_styles/issues/3122456]

Note: I have a preference _to not_ use JSON:API Extras and instead try to find ways to improve core for customization.

#### Simple OAuth

(Client secret is now required for non-confidential apps)[https://www.drupal.org/project/simple_oauth/issues/3189147]
