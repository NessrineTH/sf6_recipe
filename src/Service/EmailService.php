<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{
    private $mailer;
    private $em;
    private $destError;
    private $defaultFrom;
    private $codeUniqueInstall;
    private $templating;

    public const exception_subject = 'kickstarter';

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, Environment $templating, $defaultFrom, $destError, $codeUniqueInstall, private $applicationName)
    {
        $this->em = $em;
        $this->defaultFrom = $defaultFrom;
        $this->destError = $destError;
        $this->codeUniqueInstall = $codeUniqueInstall;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function getTemplating()
    {
        return $this->templating;
    }

    private function tryToSend(Email $message, $dest)
    {
        $res = true;
        try {
            $message->to($dest);
            $this->mailer->send($message);
        } catch (\Throwable $e) {
            $this->sendToTechnique(self::exception_subject . $e->getMessage());
            $res = false;
        } catch (\Exception $e) {
            $this->sendToTechnique(self::exception_subject . $e->getMessage());
            $res = false;
        }

        return $res;
    }

    public function sendToTechnique($errorMsg, $subject = 'erreur détectée', array $files = [], $overrideTo = false)
    {
        $subject = $this->applicationName . ' - ' . $subject;

        $htmlBody = $this->templating->render(
            'mail/technique/error_message.html.twig',
            ['message' => $errorMsg]
        );

        $dest = $overrideTo ? $overrideTo : $this->destError;

        $mail = (new Email())
            ->subject('[' . $this->codeUniqueInstall . '] ' . $subject)
            ->from(new Address($this->defaultFrom))
            ->to($dest)
            ->html($htmlBody);

        foreach ($files as $name => $content) {
            $mail->attach($content, $name);
        }

        $this->mailer->send($mail);
    }
    
}
