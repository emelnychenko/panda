<?php
/**
 *  Panda PHP Foundation, Packages and Framework
 *
 *  @package Panda
 *  @author  Eugen Melnychenko
 *  @since   v1.2.0
 */

namespace Panda\Mail;

/**
 *  Mail Smtp
 *
 *  @subpackage Mail
 */
class Smtp
{
    /**
     *  @var resource
     */
    protected $socket;

    /**
     *  @var string
     */
    protected $host;

    /**
     *  @var numeric
     */
    protected $port       = 25;

    /**
     *  @var numeric
     */
    protected $timeout    = 1;

    /**
     *  @var bool
     */
    protected $debug      = false;

    /**
     *  @var string
     */
    protected $auth       = 'login';

    /**
     *  @var string
     */
    protected $prefix;

    /**
     *  @var string
     */
    protected $secure;

    /**
     *  @var string
     */
    protected $username;

    /**
     *  @var string
     */
    protected $password;

    /**
     *  @var string
     */
    protected $type           = 'text';

    /**
     *  @var array
     */
    protected $headers        = [];

    /**
     *  @var array
     */
    protected $sender         = [];

    /**
     *  @var array
     */
    protected $reply_to       = [];

    /**
     *  @var array
     */
    protected $receivers      = [];

    /**
     *  @var string
     */
    protected $subject;

    /**
     *  @var string
     */
    protected $message;

    /**
     *  @var resource
     */
    public function __construct(array $configuration = null)
    {
        if (
            isset($configuration)
        ) {
            foreach ($configuration as $keyname => $value) {
                switch ($keyname) {
                    case 'debug':
                        $this->debug($value);
                        break;

                    case 'auth':
                        $this->auth($value);
                        break;

                    case 'username':
                        $this->username($value);
                        break;

                    case 'password':
                        $this->password($value);
                        break;

                    case 'host':
                        $this->host($value);
                        break;

                    case 'port':
                        $this->port($value);
                        break;

                    case 'secure':
                        $this->secure($value);
                        break;

                    case 'type':
                        $this->type($value);
                        break;

                    case 'sender':
                        if (
                            is_array($value)
                        ) {
                            if (
                                isset($value['address'], $value['name'])
                            ) {
                                $this->reply_to($value['address'], 'noreply');
                                $this->sender($value['address'], $value['name']);
                            }
                        } else {
                            $this->reply_to($value);
                            $this->sender($value);
                        }

                        break;
                }
            }
        }
    }

    /**
     *  Change host server.
     *
     *  @var string $host
     */
    public function host($host)
    {
        if (
            is_string($host)
        ) {
            $this->host = $host;
        }

        return $this;
    }

    /**
     *  Change port.
     *
     *  @var numeric $port
     */
    public function port($port)
    {
        if (
            is_numeric($port)
        ) {
            $this->port = $port;
        }

        return $this;
    }

    /**
     *  Change auth type.
     *
     *  @var string $port
     */
    public function auth($auth)
    {
        if (
            is_string($auth) && in_array($auth, ['login', 'plain'])
        ) {
            $this->auth = $auth;
        }

        return $this;
    }

    /**
     *  Change secure type: ssl || tls.
     *
     *  @var string $secure
     */
    public function secure($secure)
    {
        if (
            is_string($secure) && in_array($secure, ['ssl', 'tls'], true)
        ) {
            $this->secure = $secure;
        }

        return $this;
    }

    /**
     *  Change content type: text || html.
     *
     *  @var string $type
     */
    public function type($type)
    {
        if (
            is_string($type) && in_array($type, ['text', 'html'], true)
        ) {
            $this->type = $type;
        }

        return $this;
    }

    /**
     *  Use debug echo?.
     *
     *  @var bool $debug
     */
    public function debug($debug)
    {
        if (
            is_bool($debug)
        ) {
            $this->debug = $debug;
        }

        return $this;
    }

    /**
     *  Set username for auth.
     *
     *  @var string $username
     */
    public function username($username)
    {
        if (
            is_string($username)
        ) {
            $this->username = base64_encode($username);
        }

        return $this;
    }

    /**
     *  Set password for auth.
     *
     *  @var string $password
     */
    public function password($password)
    {
        if (
            is_string($password)
        ) {
            $this->password = base64_encode($password);
        }

        return $this;
    }

    /**
     *  Set sender address "FROM".
     *
     *  @var string $address
     *  @var mixed $name
     */
    public function sender($address, $name = null)
    {
        if (
            is_string($address)
        ) {
            $this->sender['address'] = $address;
        }

        if (
            isset($name) && is_string($name)
        ) {
            $this->sender['name'] = $name;
        }

        return $this;
    }

    /**
     *  Set receivers addresses "TO".
     *
     *  @var mixed $addresses
     *  @var mixed $name
     */
    public function receiver($addresses, $name = null)
    {
        if (
            is_array($addresses)
        ) {
            foreach ($addresses as $address => $_name) {
                if (
                    is_numeric($address)
                ) {
                    $this->receivers[$_name]     = null;
                } else {
                    $this->receivers[$address]  = $_name;
                }
            }
        } elseif (
            is_string($addresses)
        ) {
            $this->receivers[$addresses]  = $name;
        }

        return $this;
    }

    /**
     *  Set reply to addresses "REPLY_TO".
     *
     *  @var mixed $addresses
     *  @var mixed $name
     */
    public function reply_to($addresses, $name = null)
    {
        if (
            is_array($addresses)
        ) {
            foreach ($addresses as $address => $_name) {
                if (
                    is_numeric($address)
                ) {
                    $this->reply_to[$_name]     = null;
                } else {
                    $this->reply_to[$address]  = $_name;
                }
            }
        } elseif (
            is_string($addresses)
        ) {
            $this->reply_to[$addresses]  = $name;
        }

        return $this;
    }

    /**
     *  Accept headers.
     *
     *  @var mixed $headers
     */
    public function headers($headers)
    {
        if (
            is_array($headers)
        ) {
            foreach ($headers as $header) {
                $this->headers[] = $header;
            }
        } elseif (
            is_string($headers)
        ) {
            $this->headers[] = $headers;
        }

        return $this;
    }

    /**
     *  Store subject.
     *
     *  @var string $subject
     */
    public function subject($subject)
    {
        if (
            is_string($subject)
        ) {
            $this->subject = $subject;
        }

        return $this;
    }

    /**
     *  Store message.
     *
     *  @var string $message
     */
    public function message($message)
    {
        if (
            is_string($message)
        ) {
            $this->message = $message;
        }

        return $this;
    }

    /**
     *  Create address and name format.
     *
     *  @var string $message
     *  @var mixed $message
     */
    private function filter_address($address, $name = null)
    {
        if (
            isset($name) && is_string($name) && is_string($address)
        ) {
            return sprintf("%s <%s>", $name, $address);
        } elseif (is_string($address)) {
            return sprintf('<%s>', $address);
        }
    }

    /**
     *  Send message.
     *
     *  @var mixed $subject
     *  @var mixed $message
     */
    public function send($subject = null, $message = null)
    {
        /**
         *  Override subject.
         */
        if (
            isset($subject)
        ) {
            $this->subject($subject);
        }

        /**
         *  Override message.
         */
        if (
            isset($message)
        ) {
            $this->message($message);
        }

        /**
         *  Open EHLO\HELO lock.
         */
        $this->ehlo     = false;

        /**
         *  Use 'ssl://' prefix on secure 'ssl'.
         */
        $this->prefix   = $this->secure === 'ssl' ? 'ssl://' : '';

        /**
         *  Bind socket client.
         */
        if (
            function_exists('stream_socket_client')
        ) {
            $this->socket = stream_socket_client(
                $this->prefix . $this->host . ":" . $this->port,
                $errno,
                $errstr,
                $this->timeout,
                STREAM_CLIENT_CONNECT
            );
        } else {
            $this->socket = fsockopen(
                $this->prefix . $this->host,
                $this->port,
                $errno,
                $errstr,
                $this->timeout
            );
        }

        /**
         *  If failed server connection.
         */
        if (
            !is_resource($this->socket)
        ) {
            var_dump('Failed to connect to server'); die;
        }

        /**
         *  Send first EHLO\HELO.
         */
        $this->send_ehlo();

        /**
         *  If secure TLS that send TLSSTART and EHLO\HELO.
         */
        if (
            $this->secure === 'tls'
        ) {
            $this->start_tls();
            $this->send_ehlo();
        }

        /**
         *  Send AUTH, FROM, TO, DATA and QUIT.
         */
        $this->send_auth();
        $this->send_rcpt();
        $this->send_message();
        $this->send_quit();

        /**
         *  Close socket connection.
         */
        fclose($this->socket);

        return $this;
    }

    /**
     *  Enable STARTTLS.
     */
    private function start_tls()
    {
        $this->send_command("TLS", "STARTTLS", 220);

        $method = STREAM_CRYPTO_METHOD_TLS_CLIENT;

        if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
            $method |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            $method |= STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT;
        }

        if (
            !@stream_socket_enable_crypto(
                $this->socket,
                true,
                $method
            )
        ) {
            return false;
        }

        return true;
    }

    /**
     *  Send EHLO\HELO.
     */
    private function send_ehlo()
    {
        $this->send_command('EHLO', 'EHLO ' . $this->host, '250');
    }

    /**
     *  Send AUTH and user identity and credential.
     */
    private function send_auth()
    {
        $this->send_command('AUTH', 'AUTH LOGIN', '334');
        $this->send_command('USR', $this->username, '334');
        $this->send_command('PWD', $this->password, '235');
    }

    /**
     *  Send FROM and all TO.
     */
    private function send_rcpt()
    {
        $this->send_command('FROM', 'MAIL FROM: <' . $this->sender['address'] . '>', '250');

        foreach ($this->receivers as $address => $name)
        {
            $this->send_command('TO', 'RCPT TO: <' . $address . '>', '250');
        }
    }


    /**
     *  Send message content.
     */
    private function send_message()
    {
        $this->send_command('DATA', 'DATA', '354');

        /**
         *  Store sender
         */
        $headers    = [
            sprintf("From: %s", $this->filter_address($this->sender['address'], $this->sender['name']))
        ];

        /**
         *  Filtrate receivers
         */
        if (
            !empty($this->receivers)
        ) {
            $receivers = [];

            foreach ($this->receivers as $address => $name)
            {
                $receivers[] = $this->filter_address($address, $name);
            }

            $headers[]  = sprintf("To: %s", implode(', ', $receivers));
        }

        /**
         *  Filtrate reply to
         */
        if (
            !empty($this->reply_to)
        ) {
            $reply_to = [];

            foreach ($this->reply_to as $address => $name)
            {
                $reply_to[] = $this->filter_address($address, $name);
            }

            $headers[]  = sprintf("Reply-To: %s", implode(', ', $reply_to));
        }

        $headers[]  = sprintf("Subject: %s", $this->subject);

        /**
         *  Accept basic MIME
         */
        $headers[] = 'Mime-Version: 1.0;';
        $headers[] = 'Content-Transfer-Encoding: 7bit;';

        /**
         *  Accept Content Type
         */
        switch ($this->type) {
            default:
            case 'text':
                $headers[] = 'Content-Type: text/plain;charset="UTF-8";';
                break;

            case 'html':
                $headers[] = 'Content-Type: text/html;charset="UTF-8";';
                break;
        }

        /**
         *  Accept headers
         */
        if (
            !empty($this->headers)
        ) {
            foreach ($this->headers as $header) {
                $headers[] = $header;
            }
        }

        /**
         *  Format mail message
         */
        $content    = sprintf(
                "%s\r\n\r\n%s",
                implode("\r\n", $headers),
                $this->message
            );

        $this->send_command('MSG', $content);

    }

    /**
     *  Send DOT QUIT commands.
     */
    private function send_quit()
    {
        $this->send_command('DOT',  '.',    250);
        $this->send_command('QUIT', 'QUIT', 250);
    }

    /**
     *  Send socket command login.
     */
    private function send_command($command_name, $command, $expected_response = 250)
    {

        $data = '';
        /**
         *  Write command to socket.
         */
        fwrite($this->socket, $command . "\r\n");

        /**
         *  Set timeout.
         */
        $expire     = time() + $this->timeout;
        stream_set_timeout($this->socket, $this->timeout);

        /**
         *  Message doesn`t return any response.
         */
        if ($command_name === 'MSG')
            return;

        /**
         *  Server reponse reader.
         */
        while (
            is_resource($this->socket) && !feof($this->socket)
        ) {
            /**
             *  Read line from IO.
             */
            $line    = fgets($this->socket, 515);
            $data   .= $line . "\n";

            /**
             *  3 space after status mean that response is over.
             */
            if (
                (isset($line[3]) and $line[3] == ' ')
            ) {
                /**
                 *  Check usage of first EHLO\HELO lock. If false that store true.
                 */
                if ($this->ehlo)
                    break;
                else
                    $this->ehlo = true;
            }

            if (time() > $expire)
                break;
        }

        /**
         *  If debug equal true than print responses.
         */
        if (
            $this->debug
        ) {
            echo "<b>" . $command_name . "</b>\n";
            echo "----------------------------\n";
            echo $data;
            echo "----------------------------\n";
        }

        return;
    }
}
