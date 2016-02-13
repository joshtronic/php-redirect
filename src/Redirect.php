<?php

/**
 * Redirect
 *
 * Redirects the browser to another URL. Stops execution as to not run code
 * erroneously due to output buffering. HTTP/1.1 request an absolute URI, hence
 * the inclusion of the scheme, hostname and absolute path if :// is not found.
 * Don't hate the player, hate the RFC.
 *
 * @link  http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.30
 * @usage joshtronic\Redirect('/dest'); // Permanent (301)
 * @usage joshtronic\Redirect('/dest', false); // Temporary (302)
 */

namespace joshtronic;

class Redirect
{
    /**
     * @param  string $destination URL to redirect to
     */
    public function __construct($url, $permanent = true)
    {
        if (strpos($destination, '://') === false) {
            if (
                !isset($_SERVER['HTTPS'])
                || $_SERVER['HTTPS'] == 'off'
                || $_SERVER['HTTPS'] == ''
            ) {
                $protocol = 'http';
            } else {
                $protocol = 'https';
            }

            $destination = $protocol . '://' . $_SERVER['HTTP_HOST'] . $destination;
        }

        if ($permanent) {
            $code    = 301;
            $message = $code . ' Moved Permanently';
        } else {
            $code    = 302;
            $message = $code . ' Found';
        }

        header('HTTP/1.1 ' . $message, true, $code);
        header('Status: '  . $message, true, $code);

        header('Location: ' . $destination);
        exit;
    }
}

