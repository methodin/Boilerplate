<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once LIB . 'routes.php';

$context = new RequestContext();

// this is optional and can be done without a Request instance
$context->fromRequest(Request::createFromGlobals());

$matcher = new UrlMatcher($routes, $context);

try
{
	$parameters = $matcher->match($_GET['url']);
	$route		= explode('.', $parameters['_route']);
	$file		= CONTROLLERS . $route[0] . '.php';
	if(file_exists($file))
	{
		require_once $file;
		$class	= '\\Controllers\\' . $route[0];
		if(is_callable(array($class, $route[1])))
		{
			$controller	= new $class();
			$response 	= $controller->{$route[1]}();
			$headers 	= apache_request_headers();
			if(strpos($headers['Accept'], '/json'))
			{
				unset($response['_template']);
				header('Content-type: application/json');
				die(json_encode($response));
			}
			else
			{
				$template 	= $response['_template'];
				unset($response['_template']);
				$response = new Response($twig->render($template, $response), 200);
			}
		}
		else
		{
			throw new Routing\Exception\ResourceNotFoundException('Action not found');
		}
	}
	else
	{
		throw new Routing\Exception\ResourceNotFoundException('Controller not found');
	}
} catch (Routing\Exception\ResourceNotFoundException $e) {
	$message 	= $e->getMessage();
	$response 	= new Response($message ? $message : 'Route not found', 404);
} catch (Exception $e) {
	$response = new Response('An error occurred', 500);
}

$response->send();
