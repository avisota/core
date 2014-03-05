Avisota core system
===================

[![Build Status](https://travis-ci.org/avisota/core.png?branch=master)](https://travis-ci.org/avisota/core) [![API DOCS](https://bit3.de/files/Icons/apidocs.png)](http://avisota.github.io/core/) [![mess checked](https://bit3.de/files/Icons/mess-checked.png)](https://github.com/bit3/php-coding-standard) [![style checked](https://bit3.de/files/Icons/style-checked.png)](https://github.com/bit3/php-coding-standard)

The Avisota core system is a message generation, queue and transport system, based on top of the great Swift Mailer.

Avisota is not designed to replace Swift Miler, but it is designed to manage the pre-ransport process.
On the one hand Swift Miler can only handle stateful `Swift_Message`s. On the other hand Avisota handles stateless `MessageInterface`'s.
You can define what a MessageInterface may be: a message template, a stateful message object or even a native `Swift_Message`.

The following chart shows what you can do with the Avisota messaging system, what in most cases is impossible with Swift Mailer.

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

As you can see, server 1 creates the message and stores it in a queue on server 2.
Server 3 reads the messages from server 2 and sends them via SMTP.
You can do this even with plain `Swift_Message` objects, but it will be difficult if you need to work with attachments for example.
With your own `MessageInterface` implementation, you have the full control in your hands.
