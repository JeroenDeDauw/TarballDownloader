<?php

namespace TarballDownloader;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class TarballDownloader {

	private $tarballBuilder;

	private $components;

	private $zipFileName;

	private $topLevelDirectory;

	private function __construct( TarballBuilder $tarballBuilder ) {
		$this->tarballBuilder = $tarballBuilder;
	}

	/**
	 * @param TarballBuilder $tarballBuilder
	 * @return $this
	 */
	public static function newInstance( TarballBuilder $tarballBuilder ) {
		return new self( $tarballBuilder );
	}

	/**
	 * @param array $components
	 * @return $this
	 */
	public function download( array $components ) {
		$this->components = $components;
		return $this;
	}

	/**
	 * @param $topLevelDirectory
	 * @return $this
	 */
	public function intoDirectory( $topLevelDirectory ) {
		$this->topLevelDirectory = $topLevelDirectory;
		return $this;
	}

	/**
	 * @param $zipFileName
	 * @return $this
	 */
	public function toFile( $zipFileName ) {
		$this->zipFileName = $zipFileName;
		return $this;
	}

	public function now() {
		$zipPath = $this->tarballBuilder->buildAndZip(
			$this->components,
			$this->zipFileName,
			$this->topLevelDirectory
		);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$this->zipFileName."\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize( $zipPath ));
		ob_end_flush();
		readfile($zipPath);
	}

}
