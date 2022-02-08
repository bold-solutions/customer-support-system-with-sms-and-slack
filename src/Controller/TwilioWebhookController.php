<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\NoRecipient;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webhooks/twilio")
 */
class TwilioWebhookController extends AbstractController
{
    /**
     * @Route("/sms/incoming", name="webhook.twilio.sms_incoming")
     * @param Request $request
     * @param NotifierInterface $notifier
     * @return Response
     */
    public function handleIncomingSmsMessage(Request $request, NotifierInterface $notifier)
    {
        $from = $request->request->get('From');
        $body = $request->request->get('Body');

        $notification = (new Notification($from, ['chat/slack']))
            ->content($body);
        $notifier->send($notification);

        return new Response();
    }
}