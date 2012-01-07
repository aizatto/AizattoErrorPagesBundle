<?php

namespace Aizatto\Bundle\ErrorPagesBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController as Controller;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

class ExceptionController extends Controller {

  protected function findTemplate($templating, $format, $code, $debug) {
    $name = $debug ? 'exception' : 'error';
    if ($debug && 'html' == $format) {
      $name = 'exception_full';
    }

    // when not in debug, try to find a template for the specific HTTP status code and format
    if (!$debug) {
      $template = $this->createTemplateReference($name.$code, $format);
      if ($templating->exists($template)) {
        return $template;
      }
    }

    // try to find a template for the given format
    $template = $this->createTemplateReference($name, $format);
    if ($templating->exists($template)) {
      return $template;
    }

    // default to a generic HTML exception
    $this->container->get('request')->setRequestFormat('html');

    return $this->createTemplateReference($name);
  }

  protected function createTemplateReference($name, $format = 'html', $engine = 'php') {
    return new TemplateReference('AizattoErrorPagesBundle', 'Exception', $name, $format, $engine);
  }

}
