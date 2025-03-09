<?php
/**
 * Converts Zoom transcript to HTML or Markdown table.
 *
 * @Author: Nikita Menshutin
 * @Author URI: https://nikita.global
 * @License: MIT
 * @Version: 1.0
 * @Date: 2025-03-08
 * @package: transcribe_zoom
 */

declare( strict_types = 1 );
/**
 * Converts Zoom transcript to HTML or Markdown table.
 */
class Transcribe_Zoom {
	/**
	 * File
	 *
	 * @var string
	 */
	private $file = '';
	/**
	 * Chunks of file
	 *
	 * @var array
	 */
	private $chunks = array();
	/**
	 * Segments
	 *
	 * @var array
	 */
	private $segments = array();
	/**
	 * Table
	 *
	 * @var string
	 */
	private $table = '';
	/**
	 * Constructor
	 *
	 * @param string $file file contents.
	 */
	function __construct( string $file ) {
		$this->file = $file;
	}

	/**
	 * Get chunks. Breaks file into chunks.
	 *
	 * @return self
	 */
	public function get_chunks(): self {
		$this->chunks = preg_split( '/^\r\n/m', $this->file );
		unset( $this->chunks[0] );
		foreach ( $this->chunks as $key => $value ) {
			$value = explode( "\r", $value );
			unset( $value[0] );
			$this->chunks[ $key ] = array_values( $value );
		}
		return $this;
	}

	/**
	 * Format chunks. Formats chunks into array.
	 *
	 * @return self
	 */
	public function format_chunks(): self {
		foreach ( $this->chunks as $key => $value ) {
			if ( count( $value ) < 2 ) {
				unset( $this->chunks[ $key ] );
				continue;
			}
			foreach ( $value as $k => $v ) {
				$value[ $k ] = trim( $v );

			}
			$this->chunks[ $key ] = array(
				'time'    => explode( ' --> ', $value[0] ),
				'raw'     => implode( "\r", $value ),
				'speaker' => explode( ': ', $value[1] )[0],
				'speach'  => explode( ': ', $value[1] )[1],
			);
		}
		return $this;
	}

	/**
	 * Make segments. Breaks chunks into segments.
	 *
	 * @return self
	 */
	public function make_segments() {
		$speaker = null;
		$text    = '';
		foreach ( $this->chunks as $chunk ) {
			if ( empty( $speaker ) ) {
				$speaker    = $chunk['speaker'];
				$time_start = $chunk['time'][0];
				$time_end   = $chunk['time'][1];
			}
			if ( $speaker === $chunk['speaker'] ) {

				$text    .= ( $chunk['speach'] . ' ' );
				$time_end = $chunk['time'][1];
			} else {
				$this->segments[] = array(
					'idx'     => count( $this->segments ) + 1,
					'speaker' => $speaker,
					'text'    => $text,
					'time'    => implode( ' - ', array( $time_start, $time_end ) ),
				);
				$speaker          = $chunk['speaker'];
				$text             = $chunk['speach'] . ' ';
				$time_start       = $chunk['time'][0];
				$time_end         = $chunk['time'][1];

			}
		}
		return $this;
	}

	/**
	 * Make table. Generates HTML table.
	 *
	 * @return self
	 */
	public function make_table(): self {
		$this->table  = '<!DOCTYPE html><html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Диалог</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>';
		$this->table .= '<table>';
		$this->table .= '<tr><th>№</th><th>Время</th><th>Участник</th><th>Речь</th></tr>';
		foreach ( $this->segments as $segment ) {
			$this->table .= '<tr>';
			$this->table .= '<td>' . $segment['idx'] . '</td>';
			$this->table .= '<td>' . $segment['time'] . '</td>';
			$this->table .= '<td>' . $segment['speaker'] . '</td>';
			$this->table .= '<td>' . $segment['text'] . '</td>';
			$this->table .= '</tr>';
		}
		$this->table .= '</table></body></html>';
		return $this;
	}

	/**
	 * Make Markdown table.
	 *
	 * @return self
	 */
	public function make_md(): self {
		$md  = '| Time | Speaker | text |' . "\n";
		$md .= '| --- | --- | --- |' . "\n";
		foreach ( $this->segments as $segment ) {
			$md .= '| ' . $segment['time'] . ' | ' . $segment['speaker'] . ' | ' . $segment['text'] . ' |' . "\n";
		}
		$this->table = $md;
		return $this;
	}

	/**
	 * Echo table.
	 *
	 * @return void
	 */
	public function echo() {
		echo $this->table;
	}
}
/**
 * Usage examples.
 */
// $transcribe = new Transcribe_Zoom();
// $transcribe->get_chunks()->format_chunks()->make_segments()->make_table()->echo();
