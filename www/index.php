<?php

//ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use TarballDownloader\TarballBuilder;
use TarballDownloader\TarballDownloader;

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

$app->get('/download', function () use ($app) {
	newTarballDownloader()
		->download( [ 'mediawiki/semantic-media-wiki' => '@stable' ] )
		->intoDirectory( 'Semantic MediaWiki (+dependencies)' )
		->toFile( 'Semantic MediaWiki (+dependencies).zip' )
		->now();
	return '';
});

$app->get('/download-all', function () use ($app) {
	newTarballDownloader()
		->download( [
			'mediawiki/semantic-media-wiki' => '@stable',
			'mediawiki/semantic-result-formats' => '@stable',
			'mediawiki/semantic-extra-special-properties' => '@stable',
			'mediawiki/semantic-maps' => '@stable',
			'mediawiki/semantic-forms' => '@stable',
		] )
		->intoDirectory( 'Semantic MediaWiki bundle' )
		->toFile( 'Semantic MediaWiki bundle.zip' )
		->now();
	return '';
});

$app->get('/download-dev', function () use ($app) {
	newTarballDownloader()
		->download( [ 'mediawiki/semantic-media-wiki' => '@dev' ] )
		->intoDirectory( 'Semantic MediaWiki (development version)' )
		->toFile( 'Semantic MediaWiki (dev).zip' )
		->now();
	return '';
});

$app->run();

function newTarballDownloader() {
	return TarballDownloader::newInstance( new TarballBuilder(
		__DIR__ . '/../build/' . sha1( mt_rand() ),
		$GLOBALS['app']['monolog']
	) );
}