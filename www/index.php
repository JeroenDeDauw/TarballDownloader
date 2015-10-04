<?php

//ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use TarballDownloader\TarballBuilder;
use TarballDownloader\TarballDownloader;
use TarballDownloader\DownloadPackageJsonReader;

$app = new Application();
$app->register(new UrlGeneratorServiceProvider());
$app->register(new TwigServiceProvider());

$app['twig.path'] = array(__DIR__.'/../templates');

$app->get('/', function () use ($app) {
	return $app['twig']->render('index.html', ['page' => 'home']);
});

$app['debug'] = true;

$app->register(new MonologServiceProvider(), array(
	'monolog.logfile' => __DIR__.'/../var/logs/silex.log',
	'monolog.level' => Monolog\Logger::DEBUG
));

$downloadPackageJsonReader = new DownloadPackageJsonReader( __DIR__ . '/../packages/packages.json' );

foreach ( $downloadPackageJsonReader->getPackageFieldDefinitionFor( 'bundle' ) as $key => $definition ) {
	$app->get('/download-' . $key, function () use ( $app, $definition ) {
		newTarballDownloader()
			->download( $definition['download'] )
			->intoDirectory( $definition['name'] )
			->toFile( $definition['to-file'] )
			->now();
		return '';
	});
}

$app->run();

function newTarballDownloader() {
	return TarballDownloader::newInstance( new TarballBuilder(
		__DIR__ . '/../build/' . sha1( mt_rand() ),
		$GLOBALS['app']['monolog']
	) );
}