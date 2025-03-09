# Transcribe Zoom

This class converts Zoom transcripts to HTML or Markdown tables.

## Author

- **Nikita Menshutin**
- **Author URI:** [https://nikita.global](https://nikita.global)

## License

This project is licensed under the MIT License.

## Version

1.0

## Date

2025-03-08

## Description

This project provides a PHP class `Transcribe_Zoom` that processes Zoom transcript files and converts them into HTML or Markdown tables.

## Usage

### Constructor

```php
$transcribe = new Transcribe_Zoom($file_contents);
```

### Methods

- `get_chunks()`: Breaks the file into chunks.
- `format_chunks()`: Formats chunks into an array.
- `make_segments()`: Breaks chunks into segments.
- `make_table()`: Generates an HTML table.
- `make_md()`: Generates a Markdown table.
- `echo()`: Outputs the generated table.

### Example

```php
// Include the Transcribe_Zoom class
require 'transcribe_zoom.php';

// File contents to be transcribed
$file_contents = file_get_contents('path/to/zoom_transcript.txt');

// Create an instance of Transcribe_Zoom
$transcribe = new Transcribe_Zoom($file_contents);

// Process the file and generate an HTML table
$transcribe->get_chunks()->format_chunks()->make_segments()->make_table()->echo();

// Process the file and generate a Markdown table
$transcribe->get_chunks()->format_chunks()->make_segments()->make_md()->echo();
```

## File Structure

- `transcribe_zoom.php`: Contains the Transcribe_Zoom class.
- `README.md`: This file.

## Requirements

- PHP 7.4 or higher

## Installation

1. Clone the repository.
2. Include `transcribe_zoom.php` in your project.
3. Use the `Transcribe_Zoom` class as shown in the example above.

## Contact

For any inquiries, please contact Nikita Menshutin.