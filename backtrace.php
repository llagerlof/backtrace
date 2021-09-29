<?php
/**
 * backtrace
 *
 * Function that returns a simple formatted backtrace string for fast human reading.
 * Each line has only the class, method, line and relative file path.
 *
 * @package    backtrace
 * @version    1.0.0
 * @author     Lawrence Lagerlof <llagerlof@gmail.com>
 * @copyright  2021 Lawrence Lagerlof
 * @link       http://github.com/llagerlof/backtrace
 * @license    https://opensource.org/licenses/MIT MIT
 */
function backtrace() {
    $calls = debug_backtrace();
    $output = '';

    // Find the larger "class + method" width.
    $method_call_output_max_size = 0;
    foreach ($calls as $i => $call) {
        if ($i !== 0) { // Ignore first backtrace entry (this file)
            $method_call_output =
                ($call['class'] ? $call['class'] . '->' : '') .
                $call['function'] .
                '()';
            if (strlen($method_call_output) > $method_call_output_max_size) {
                $method_call_output_max_size = strlen($method_call_output);
            }
        }
    }

    // Format output
    foreach ($calls as $i => $call) {
        if ($i !== 0) { // Ignore first backtrace entry (this file)
            // Replace backslash for slash in document root.
            $document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
            // First column is class + method or only function
            $method_call_output =
                ($call['class'] ? $call['class'] . '->' : '') .
                ($call['function'] ? $call['function'] . '()' : '');
            // Replace backslash for slash in backtrace path.
            $file_full_path = str_replace('\\', '/', $call['file']);
            // Remove document root path from file path.
            $file_relative_path = str_replace($document_root, '', $file_full_path);
            // Format line and align columns
            $output .=
                str_pad($method_call_output, $method_call_output_max_size + 2, ' ') .
                str_pad($call['line'], 4, ' ', STR_PAD_LEFT) .
                '  |  ' .
                $file_relative_path .
                "\n";
        }
    }

    return $output;
}
