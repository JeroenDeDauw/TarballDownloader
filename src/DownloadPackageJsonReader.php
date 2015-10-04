<?php

namespace TarballDownloader;

use RuntimeException;

/**
 * @license GNU GPL v2+
 * @since 0.1
 *
 * @author mwjames
 */
class DownloadPackageJsonReader {

	/**
	 * @var string
	 */
	private $file;

	/**
	 * @var array
	 */
	private $content = array();

	/**
	 * @since 0.1
	 *
	 * @param string $file
	 */
	public function __construct( $file ) {
		$this->file = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $file );
	}

	/**
	 * @since 0.1
	 *
	 * @return array
	 */
	public function getPackageFieldDefinitionFor( $field ) {

		if ( $this->content === array() ) {
			$this->content = $this->getFileContents( $this->file );
		}

		if ( isset( $this->content[$field] ) ) {
			return $this->content[$field];
		}

		throw new RuntimeException( "Cannot access $field" );
	}

	private function getFileContents( $file ) {

		$contents = json_decode( file_get_contents( $file ), true );

		if ( $contents !== null && json_last_error() === JSON_ERROR_NONE ) {
			return $contents;
		}

		throw new RuntimeException( $this->printDescriptiveJsonError( json_last_error() ) );
	}

	private function printDescriptiveJsonError( $errorCode ) {

		$errorMessages = array(
			JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch, malformed JSON',
			JSON_ERROR_CTRL_CHAR => 'Unexpected control character found, possibly incorrectly encoded',
			JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
			JSON_ERROR_UTF8   => 'Malformed UTF-8 characters, possibly incorrectly encoded',
			JSON_ERROR_DEPTH  => 'The maximum stack depth has been exceeded'
		);

		return sprintf(
			"Expected a JSON compatible format but failed with '%s'",
			$errorMessages[$errorCode]
		);
	}

}
