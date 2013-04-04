<?php

use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator;
use Symfony\Bundle\FrameworkBundle\Templating\Loader\FilesystemLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\CoreExtension as FormExtension;
use Symfony\Component\Form\Extension\Templating\TemplatingExtension as FormTemplatingExtension;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use My\SimpleFormType;

require_once __DIR__.'/lib/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$autoloader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
$autoloader->registerNamespace('Symfony', __DIR__.'/lib/');
$autoloader->registerNamespace('My', __DIR__.'/lib/');
$autoloader->register();

$container = new Container();

$formExtension = new FormExtension($container, array(), array(), array());
$resolvedFormTypeFactory = new ResolvedFormTypeFactory();
$registry = new FormRegistry(array($formExtension), $resolvedFormTypeFactory);
$formfactory = new FormFactory($registry, $resolvedFormTypeFactory);

$form = $formfactory->create(new SimpleFormType());

$request = Request::createFromGlobals();
if ($request->isMethod('post'))
{
  $form->bind($request);
  if ($form->isValid())
  {
    var_dump($form->getData());die();
  }
}

$locator = new FileLocator(array(__DIR__.'/views', __DIR__.'/../lib/Symfony/Bundle/FrameworkBundle/Resources/views'));
$templateLocator = new TemplateLocator($locator);
$loader = new FilesystemLoader($templateLocator);
$view = new PhpEngine(new TemplateNameParser(), $loader);

$formHelperExtension = new FormTemplatingExtension($view, null, array('FrameworkdBundle:Form'));

echo $view->render('hello.php', array('form' => $form->createView()));