<?php

namespace TarballDownloader\Tests\integration;

use TarballDownloader\TarballBuilder;

/**
 * @covers TarballDownloader\TarballBuilder
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TarballBuilderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var TarballBuilder
	 */
	private $builder;

	public function setUp() {
		$this->builder = new TarballBuilder( '/tmp/tarball-build' );
	}

	public function tearDown() {
		exec( 'rm -r /tmp/tarball-build' );
	}

	public function testBuilderCreatesComposerJson() {
		$buildPath = $this->builder->build( [ 'diff/diff' => '@stable' ] );

		$this->assertFileExists( $buildPath . '/composer.json' );
	}

	public function testBuilderCreatesComponents() {
		$buildPath = $this->builder->build( [ 'diff/diff' => '@stable' ] );

		$this->assertFileExists( $buildPath . '/vendor/autoload.php' );
		$this->assertFileExists( $buildPath . '/vendor/diff/diff/Diff.php' );
	}

	public function testBuildAndZip() {
		$zipPath = $this->builder->buildAndZip( [ 'diff/diff' => '@stable' ], 'kittens.zip' );

		$this->assertSame( '/tmp/tarball-build/kittens.zip', $zipPath );
		$this->assertFileExists( '/tmp/tarball-build/kittens.zip' );
		$this->assertFileInZip( $zipPath, 'vendor/autoload.php' );
	}

	private function assertFileInZip( $zipPath, $relativeFilePath ) {
		exec( 'rm -r /tmp/testing-tarball-stuff' );
		mkdir( '/tmp/testing-tarball-stuff' );
		chdir( '/tmp/testing-tarball-stuff' );
		exec( 'unzip ' . escapeshellarg( $zipPath ) );
		$this->assertFileExists( '/tmp/testing-tarball-stuff/' . $relativeFilePath );
	}

	public function testBuildAndZipWithDirectory() {
		$zipPath = $this->builder->buildAndZip(
			[ 'diff/diff' => '@stable' ],
			'kittens.zip',
			'StuffBeHere'
		);

		$this->assertFileInZip( $zipPath, 'StuffBeHere/vendor/autoload.php' );
	}

}
