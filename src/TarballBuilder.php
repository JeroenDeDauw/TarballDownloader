<?php

namespace TarballDownloader;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TarballBuilder {

	private $buildDirectory;

	private $composerCommand;

	public function __construct( $buildDirectory, $composerCommand = 'composer' ) {
		$this->buildDirectory = $buildDirectory;
		$this->composerCommand = $composerCommand;
	}

	public function build( array $components ) {
		$this->prepareBuildDirectory();
		$this->createComposerJson( $components );
		$this->runComposerUpdate();

		return $this->buildDirectory;
	}

	private function prepareBuildDirectory() {
		exec( 'rm -r ' . escapeshellarg( $this->buildDirectory ) );
		mkdir($this->buildDirectory );
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
		chdir( $this->buildDirectory );
		exec( escapeshellcmd( $this->composerCommand ) . ' update' );
	}

	public function buildAndZip( array $components, $zipName, $topLevelDirectory = '' ) {
		$buildPath = $this->build( $components );

		chdir( $this->buildDirectory );

		if ( $topLevelDirectory !== '' ) {
			mkdir( $topLevelDirectory );
			exec( 'mv * ' . escapeshellarg( $topLevelDirectory ) );
		}

		exec( 'zip -r ' . escapeshellarg( $zipName ) . ' .' );

		return $buildPath . '/' . $zipName;
	}

}
