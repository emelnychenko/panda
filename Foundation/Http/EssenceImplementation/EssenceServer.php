<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda Foundation
 *  @author  Eugen Melnychenko
 */

namespace Panda\Foundation\Http\EssenceImplementation;

use Panda\Foundation\Support\EssenceReadableInstance;

/**
 *  Client Request Essence Server
 *
 *  @subpackage Http
 */
class EssenceServer extends EssenceReadableInstance
{
    /**
     *  Get client locale or verify if locale is $question.
     *
     *  @var mixed $default
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
            $language = array();

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

    public function is_json()
    {
        return preg_match(
            '/(text|application)\/json/i', $this->get('CONTENT_TYPE')
        );
    }

    /**
     *  Get uri path.
     *
     *  @var mixed $default
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
     *  @var mixed $default
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
     *  @var mixed $default
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
     *  @var mixed $default
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
     *  @var mixed $default
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
     *  @var mixed $default
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
     *  @var mixed $default
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

