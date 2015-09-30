<?php

namespace TarballDownloader;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TarballBuilder {

	private $buildDirectory;

	private $composerCommand;

	private $logger;

	public function __construct( $buildDirectory, LoggerInterface $logger = null ) {
		$this->buildDirectory = $buildDirectory;
		$this->logger = $logger === null ? new NullLogger() : $logger;
		$this->composerCommand = 'composer';
	}

	public function build( array $components ) {
		$this->prepareBuildDirectory();
		$this->createComposerJson( $components );
		$this->runComposerUpdate();

		return $this->buildDirectory;
	}

	private function prepareBuildDirectory() {
		$this->removeBuildFiles();
		mkdir( $this->buildDirectory );
	}

	private function createComposerJson( array $components ) {
		$json = [
			'require' => $components
		];

		file_put_contents(
			$this->buildDirectory . '/composer.json',
			json_encode( $json, JSON_PRETTY_PRINT )
		);
	}

	private function runComposerUpdate() {
		$commandOutput = [];

		exec(
			escapeshellcmd( $this->composerCommand )
				. ' install --no-scripts --optimize-autoloader --no-ansi --ignore-platform-reqs --working-dir '
				. escapeshellarg( $this->buildDirectory ) . ' 2>&1',
			$commandOutput
		);

		array_map( [$this->logger, 'debug'], $commandOutput );
	}

	public function buildAndZip( array $components, $zipName, $topLevelDirectory = '' ) {
		$buildPath = $this->build( $components );

		chdir( $this->buildDirectory );

		if ( $topLevelDirectory !== '' ) {
			mkdir( $topLevelDirectory );
			exec( 'mv * ' . escapeshellarg( $topLevelDirectory ) . ' 2>&1' );
		}

		exec( 'zip -r ' . escapeshellarg( $zipName ) . ' .' );

		return $buildPath . '/' . $zipName;
	}

	public function removeBuildFiles() {
		exec( 'rm -r ' . escapeshellarg( $this->buildDirectory ). ' 2>&1' );
	}

}
