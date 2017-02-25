<?php

namespace Application\Back\Mail;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part;
use Zend\View\Renderer\RendererInterface;

/**
 * Class Sender
 * @package Application\Back\Mail
 */
class Sender
{

    /**
     * @var Smtp
     */
    private $transport;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * Sender constructor.
     * @param array $smtpOptions
     */
    public function __construct(array $smtpOptions, RendererInterface $renderer)
    {
        $this->transport = new Smtp(
            new SmtpOptions(
                $smtpOptions
            )
        );

        $this->renderer = $renderer;
    }

    /**
     * @param $subject
     * @param $to
     * @param $template
     * @param $params
     * @return bool
     */
    public function sendMail($subject, $to, $template, $params = [])
    {
        $message = new Message();

        $html = new Part($this->renderer->render('email/' . $template, $params));
        $html->type = "text/html";

        $body = new \Zend\Mime\Message();
        $body->setParts([$html]);

        $message->setSubject($subject)
            ->setBody($body)
            ->setTo((array)$to)
            ->setFrom('no-reply@employee-app');

        try {
            $this->transport->send($message);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

}