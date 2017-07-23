# VocabBot - IRC Bot
#### Natural Language Chat Bot on IRC

Written in PHP, this bot listens for incoming private messages on the Mibbit IRC network. It is primarily an English vocabulary assistant that defines words but also responds to basic greetings.

### Steps to chat with VocabBot

1) Use an IRC client. Examples - https://www.mibbit.com/ (web client), AndroIRC (for Android), irssi (a CLI client), etc.

2) Connect to the server irc.mibbit.net

3) There is no need to join any IRC channel. Simply send a private message to "VocabBot". /query VocabBot

4) Once in a private chat with VocabBot, send "help" to know how it communicates.

### Setup (run your own bot)

1) Download or clone this repository. 

2) Make sure PHP is installed on the target system.

3) This program calls the <a href="http://developer.wordnik.com/" target="_blank">Wordnik API</a>, so you need to make an account and obtain an API key. 

#### Configuring your API key
- Create a file called my_api_key.php in your project directory.
- Insert the following code in my_api_key.php.
````php
<?php
  // Replace APIKEY with the API key you obtained from Wordnik
  $my_api_key = "APIKEY";
?>
````

#### Back to setup

4) This exact code will not be able to connect to the Mibbit network because I already have an instance of it running on AWS with the nickname "VenusBot". You will need to change the "nick", "ident" and "gecos" variables of your bot in index.php. You could also connect to a different IRC network instead of Mibbit. 

4) Run index.php indefinitely. eg. The command <pre>php index.php &</pre> runs index.php in the background on Linux systems. If you do not want the program to be interrupted by hangup signals, use <pre>nohup php index.php &</pre> You could also <a href="http://kvz.io/blog/2009/01/09/create-daemons-in-php/" target="_blank">create a PHP daemon like this.</a>


### Configuring Responses

1) The bot knows what to say from a set of responses located in "bot_responses.txt". <b>Avoid editing this file directly.</b> It is much easier to edit the responses to what you want in "responses.php". Responses to a particular event are stored in an array. eg. <pre>'greetings' => array("Hey", "Hello", "Hi");</pre> Edit, add, or remove responses as you wish.

2) Run this PHP script (responses.php) to generate bot_responses.txt. The responses are stored in a JSON string.

3) index.php reads from this file automatically

### Taking it forward

- To enhance the capability of the bot on IRC, follow the <a href="https://tools.ietf.org/html/rfc1459" target="_blank">IRC documentation</a>. For example, the bot can be made to respond to group messages or other IRC events.
- "Understanding" user messages happens with the help of regular expression rules. Other parsing techniques, or more advanced NLP algorithms could be used. Excellent conversation frameworks such as <a href="https://api.ai/" target="_blank">api.ai</a> and <a href="https://wit.ai/" target="_blank">wit.ai</a> also exist.

##### Extra
This project was the result of my growing interest in NLP and my obsession with IRC at one point of time. Note that IRC might not be the best platform to run your applications. It is rather outdated and very few people know about it, let alone use it.<br><b>But it's still really cool.</b>
