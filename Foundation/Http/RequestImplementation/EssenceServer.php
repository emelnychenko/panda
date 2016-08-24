<?php
/**
 *  Panda PHP Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http\RequestImplementation;

use Panda\Foundation\Support\EssenceReadableInstance;

/**
 *  Client Request Processor
 *
 *  @subpackage Http
 */
class EssenceServer extends EssenceReadableInstance
{
    /**
     *  Get client locale or verify if locale is $question.
     *
     *  @return mixed
     */
    public function locale($default = 'en')
    {
        if (
            preg_match_all(
                '/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/', 
                strtolower(
                    $this->get('HTTP_ACCEPT_LANGUAGE')
                ),
                $matches
            )
        ) {
            $language = [];

            foreach (array_combine($matches[1], $matches[2]) as $key => $value) {
                $language[$key] = empty($value) ? 1 : (float) $value;
            }

            arsort($language, SORT_NUMERIC);

            $language   = array_keys($language);
            $primary    = array_shift(
                $language
            );

            return strtok($primary, '-');
        }
        
        return $default;
    }

    /**
     *  Get uri path.
     *
     *  @return string
     */
    public function uri($default = null)
    {
        return $this->get('REQUEST_URI', $default);
    }

    /**
     *  Get url path (without uri).
     *
     *  @return string
     */
    public function url($default = null)
    {
        return strtok($this->uri($default), '?');
    }

    /**
     *  Get request method or verify if method is $question.
     *
     *  @return mixed
     */
    public function method($default = 'GET')
    {
        $method = $this->get('REQUEST_METHOD');

        return isset($method) ? $method : $default;
    }

    /**
     *  Get client IP.
     *
     *  @return string
     */
    public function ip($default = '127.0.0.1')
    {
        return $this->get('REMOTE_ADDR', $default);
    }

    /**
     *  Get client user agent.
     *
     *  @return string
     */
    public function agent($default = null)
    {
        return $this->get('HTTP_USER_AGENT', $default);
    }

    /**
     *  Get domain.
     *
     *  @return string
     */
    public function domain($default = null)
    {
        return $this->get('HTTP_HOST', $default);
    }

    /**
     *  Get document root.
     *
     *  @return string
     */
    public function docroot($default = null)
    {
        return $this->get('DOCUMENT_ROOT', $default);
    }

    /**
     *  Check if request XMLHttpRequest.
     *
     *  @return bool
     */
    public function xmlhttprequest()
    {
        return 'xmlhttprequest' == strtolower(
            $this->get('HTTP_X_REQUESTED_WITH')
        );
    }
}

