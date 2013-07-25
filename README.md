Avisota core system
===================

[![Build Status](https://travis-ci.org/avisota/core.png?branch=master)](https://travis-ci.org/avisota/core) [![API DOCS](https://bit3.de/files/Icons/apidocs.png)](http://avisota.github.io/core/) [![mess checked](https://bit3.de/files/Icons/mess-checked.png)](https://github.com/bit3/php-coding-standard) [![style checked](https://bit3.de/files/Icons/style-checked.png)](https://github.com/bit3/php-coding-standard)

The Avisota core system is a message generation, queue and transport system, based on top of the great swift mailer.

Avisota is not designed to replace swift mailer, but it is designed to manage the pre transport process.
On the one hand swift mailer can only handle stateful `Swift_Message`'s. On the other hand Avisota handle stateless `MessageInterface`'s.
You can define what a MessageInterface may be, a message template, a stateful message object or even a native `Swift_Message`.

The following chart show what you can do with the Avisota messaging system, what it is impossible in most cases with swift.

```
------------
| Server 1 | -\
------------   |
               V
         create message
               |                   ------------
               V                   | Server 3 |
        enqueue message            ------------
               |                        |
               V                        V
      store in database on     get messages from queue
               |                        |
               V                        |
          ------------ /----------------/
          | Server 2 |
          ------------ \-----\
                             |
                             V
                     send via smtp (swift)
```

As you can see, server 1 create the message and store in a queue on server 2.
Server 3 read the messages from server 2 and send them via SMTP.
You even can do this with plain `Swift_Message` objects, but it will be difficult if you need to work with attachments for example.
With your own `MessageInterface` implementation, you have the full control in your hands.
