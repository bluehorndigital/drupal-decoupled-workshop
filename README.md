# Decoupled Drupal Workshop

👋 Welcome to the [Bluehorn Digital](https://bluehorn.digital/) Decoupled Drupal workshop repository!

This repository contains a decoupled ready Drupal build with example frontend applications and HTTP requests. There is a `workshop_demo` module which turns the Umami demo profile into a headless content repository.

## What is here?

### http folder

These are HTTP files that can be executed within your IDE or editor!

### react-demo folder

A very basic Create React App that uses dynamic routing – the only two hardcoded requests are for the main menu and decoupled router.

### web fodler

Drupal!

## Running the workshop

This was first built with users who primarily use Lando. So for now, Lando the is the preferred way to run the Drupal site.

```
lando start
bash scripts/setup-workshop.sh
```

## Notes

### Required patches

Unfortunately, things don't always work well out of the box. This project contains several patches that you might want to be aware of, when building your own decoupled Drupal project. Always review `composer.patches.json` to see the latest patches.

#### Decoupled Router

[Cannot generate the entity canonical url by passing the entity UUID](https://www.drupal.org/project/decoupled_router/issues/3116487)

#### Consumer Image Styles

[Make JSON:API Extras an optional dependency](https://www.drupal.org/project/consumer_image_styles/issues/3171903)
[JsonApiResource Link $rel is now a string, not an array; getLinkRelationTypes() is now getLinkRelationType()](https://www.drupal.org/project/consumer_image_styles/issues/3122456)

Note: I have a preference _to not_ use JSON:API Extras and instead try to find ways to improve core for customization.

#### Simple OAuth

[Client secret is now required for non-confidential apps](https://www.drupal.org/project/simple_oauth/issues/3189147)
