<?php declare(strict_types=1);

namespace Drupal\workshop_demo\EventSubscriber;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Url;
use Drupal\decoupled_router\PathTranslatorEvent;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

final class ViewsPathTranslatorSubscriber implements EventSubscriberInterface {

  /**
   * The router.
   *
   * @var \Symfony\Component\Routing\Matcher\UrlMatcherInterface
   */
  protected $router;

  public function __construct(UrlMatcherInterface $router) {
    $this->router = $router;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // Run before RouterPathTranslatorSubscriber.
    $events[PathTranslatorEvent::TRANSLATE][] = ['onPathTranslation', 10];
    return $events;
  }

  public function onPathTranslation(PathTranslatorEvent $event) {
    $response = $event->getResponse();
    if (!$response instanceof CacheableJsonResponse) {
      return;
    }
    $path = $event->getPath();
    $path = $this->cleanSubdirInPath($path, $event->getRequest());
    try {
      $match_info = $this->router->match($path);
    }
    catch (ResourceNotFoundException $exception) {
      // If URL is external, we won't perform checks for content in Drupal,
      // but assume that it's working.
      if (UrlHelper::isExternal($path)) {
        $response->setStatusCode(200);
        $response->setData([
          'resolved' => $path,
        ]);
      }
      return;
    }
    catch (MethodNotAllowedException $exception) {
      $response->setStatusCode(403);
      return;
    }
    if (empty($match_info['view_id']) && empty($match_info['display_id'])) {
      return;
    }
    // Do not process if this is a View replacing an entity's canonical route.
    if (strpos($match_info[RouteObjectInterface::ROUTE_NAME], 'entity.') === 0) {
      return;
    }
    $view_id = $match_info['view_id'];
    $display_id = $match_info['display_id'];

    $resolved_url = Url::fromRoute($match_info[RouteObjectInterface::ROUTE_NAME])
      ->setAbsolute()
      ->toString(TRUE);
    $response->addCacheableDependency($resolved_url);

    $jsonapi_url = Url::fromRoute("jsonapi_views.{$view_id}.{$display_id}")
      ->setAbsolute()
      ->toString(TRUE);
    $response->addCacheableDependency($jsonapi_url);

    $output = [
      'resolved' => $resolved_url->getGeneratedUrl(),
      'views' => [
        'view_id' => $view_id,
        'display_id' => $display_id,
        'title' => $match_info['_title']
      ],
      'jsonapi' => [
        'url' => $jsonapi_url->getGeneratedUrl(),
      ],
    ];

    $response->setStatusCode(200);
    $response->setData($output);
    $event->stopPropagation();
  }

  /**
   * Removes the subdir prefix from the path.
   *
   * @param string $path
   *   The path that can contain the subdir prefix.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request to extract the path prefix from.
   *
   * @return string
   *   The clean path.
   */
  protected function cleanSubdirInPath($path, Request $request) {
    // Remove any possible leading subdir information in case Drupal is
    // installed under http://example.com/d8/index.php
    $regexp = preg_quote($request->getBasePath(), '/');
    return preg_replace(sprintf('/^%s/', $regexp), '', $path);
  }

}
