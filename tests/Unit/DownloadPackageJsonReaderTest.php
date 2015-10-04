<?php

namespace TarballDownloader\Tests;

use TarballDownloader\DownloadPackageJsonReader;
use SMW\DIWikiPage;

/**
 * @covers \TarballDownloader\DownloadPackageJsonReader
 * @group tarball-downloader
 *
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class DownloadPackageJsonReaderTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {

		$this->assertInstanceOf(
			'\TarballDownloader\DownloadPackageJsonReader',
			new DownloadPackageJsonReader( 'foo' )
		);
	}

	public function testGetPackageDefinitionForBundleField() {

		$instance = new DownloadPackageJsonReader( __DIR__ . '/../Fixtures/packages.json' );

		$this->assertInternalType(
			'array',
			$instance->getPackageFieldDefinitionFor( 'bundle' )
		);
	}

	public function testGetPackageDefinitionForInvalidFieldThrowsException() {

		$instance = new DownloadPackageJsonReader( __DIR__ . '/../Fixtures/packages.json' );

		$this->setExpectedException( 'RuntimeException' );
		$instance->getPackageFieldDefinitionFor( 'foo' );
	}

}
