<?php

require_once __DIR__ . '/transcribe_zoom.php';
$file = ! empty( $argv[1] ) ? $argv[1] : 'transcript.vtt';
if ( ! file_exists( $file ) ) {
	echo "File not found: $file\n";
	exit( 1 );
}
$transcribe = new Transcribe_Zoom( file_get_contents( $file ) );
$transcribe->get_chunks()->format_chunks()->make_segments()->make_table()->echo();
