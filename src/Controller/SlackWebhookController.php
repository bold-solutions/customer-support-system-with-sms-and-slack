<?php
namespace App\Controller;

use App\Service\SlackApiClient;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/webhooks/slack")
 */
class SlackWebhookController extends AbstractController
{
    /**
     * @Route("/events", name="webhook.slack.action")
     * @param Request $request
     * @return Response
     */
    public function handleSlackEvent(Request $request, SlackApiClient $slackApiClient, NotifierInterface $notifier, LoggerInterface $logger)
    {
        $data = json_decode($request->getContent(), true);
        $event = $data['event'];

        // Ignore bot messages
        if (array_key_exists('subtype', $event) && $event === 'bot_message') {
            return new JsonResponse();
        }

        // Ignore messages that don't belong to a thread
        if (!array_key_exists('thread_ts', $event)) {
            return new JsonResponse();
        }

        $messageId = $event['ts'];
        $threadId = $event['thread_ts'];

        // This is the thread parent message which we're also not interested in
        if ($messageId === $threadId) {
            return new JsonResponse();
        }

        $messages = $slackApiClient->getConversationReplies($threadId);
        $parent = $messages[0];
        $firstBlock = $parent['blocks'][0];
        $customerPhoneNumber = $firstBlock['text']['text'];

        $logger->log(LogLevel::DEBUG, gettype($customerPhoneNumber));
        $logger->log(LogLevel::DEBUG, $customerPhoneNumber);

        $supportResponseMessage = $event['text'];
        $logger->log(LogLevel::DEBUG, $supportResponseMessage);

        $notifier->send(
            new Notification($supportResponseMessage, ['sms']),
            new Recipient('', strval($customerPhoneNumber))
        );

        return new JsonResponse();
    }
}