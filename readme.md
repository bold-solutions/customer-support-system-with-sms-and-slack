Followed [this tutorial](https://www.twilio.com/blog/build-two-way-customer-support-system-sms-slack-symfony-notifier)
to build a Two-way Customer Support System with SMS and Slack using Symfony Notifier

Made few changes along the way to make it work with the
latest Symfony version.


How to run?
1. `symfony server:start`
2. `ngrok http 8000`
3. Setup `.env.local`
   
    ```
    TWILIO_DSN=twilio://XXX:XXX@default?from=XXX
    SLACK_DSN=slack://xoxb-XXX-XXX-XXX@default?channel=XXX
    SLACK_ACCESS_TOKEN=xoxb-XXX-XXX-XXX
    SLACK_CHANNEL=XXX
    DATABASE_URL="mysql://user:pass@127.0.0.1:3306/database_name?serverVersion=5.7"
   ```
   
4. Configure Twilio and Slack settings as guided in the tutorial