<?php

namespace Linku\Linku;

Use Mail;


class Mailer
{
    protected $template;
    protected $data;
    protected $html;
    protected $text;

    protected $attachments;

    protected static $to;
    protected static $replayTo;
    protected static $from;
    protected static $subject;

    public function sendEmail($template, $subject, $data)
    {


        $this->template = [
            'html' => 'email.' . strtolower($template) . '.html',
            'text' => 'email.' . strtolower($template) . '.text'
        ];

        $this->data = $data;
        $this->data['subject'] = $subject;

        static::$replayTo = (isset($data['replayTo']) && is_array($data['replayTo'])) ? $data['replayTo'] : config('mail.from');
        static::$from = (isset($data['from']) && is_array($data['from'])) ? $data['from'] : config('mail.from');
        static::$to = $data['to'];
        static::$subject = $subject;

        $this->prepareTemplate();

        $this->send();
    }

    public function prepareTemplate()
    {
        $inliner = new InlineCss($this->template['html'], $this->data);
        $this->data['content'] = $inliner->convert();
        $this->template['html'] = 'email.raw';
    }

    public function send()
    {
        Mail::send($this->template, $this->data, function($message)
        {
            $message->replyTo(static::$replayTo['address'], static::$replayTo['name']);
            $message->from(static::$from['address'], static::$from['name']);
            $message->sender(static::$from['address'], static::$from['name']);
            $message->to(static::$to['address'], static::$to['name']);
            $message->subject(static::$subject);
        });
    }


    /**
     * @param  $message
     */
    public static function messageBuilder($message)
    {
        $message->replyTo(static::$replayTo['address'], static::$replayTo['name']);
        $message->from(static::$from['address'], static::$from['name']);
        $message->to(static::$to['address'], static::$to['name']);
        $message->subject(static::$subject);
//            $message->priority($level);
//            $message->attach($pathToFile, array $options = []);
    }

    public static function prepareTo($email)
    {
        return [
            'name' => static::getNameFromEmail($email),
            'address' => $email
        ];
    }

    public static function getNameFromEmail($email)
    {
        $name = explode('@', $email)[0];

        return $name ? preg_replace("/[\W_]/", " ", $name) : $email;
    }
}