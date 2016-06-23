<?php

  /*Program to convert Venus' responses to JSON format and store JSON string in a text file.
    The text file can then be read by any language that supports JSON.
  */

  /*EDIT responses here, then run the code*/
  $responses = array(
    'greetings' => array("Hey", "Hello", "Hi"),
    'exclamation' => array("!", ""),
    'smileys' => array(":)", ";)", ":~"),
    'ty_responses' => array("No problem!", "\001ACTION blushes.\001", "You're welcome!"),
    'bye_responses' => array("Bye!", "See you later!", "Goodbye!", "\001ACTION disapparates.\001"),
    'unknownmsg_responses' => array("I didn't understand that.", "I don't know what that means!", "I'm not sure how to reply to that."),
    'whatsup_replies' => array("I'm just snacking on some words.", "I feel like defining a word..", "I just finished reading a dictionary!", "I'm looking for patterns in clouds. It's called pareidolia!", "I'm studying Greek!", "I'm studying Latin!", "Practising my calligraphy!"),
    'helpmsg' => "Hi! I'm \x0313Venus\x03, a word assistant. If you want me to define a word, you can ask me things like '\x0313define <word>\x03', '\x0313What's a <word>?\x03' or simply '\x0313<word>?\x03' (your word followed by a question mark), etc. If you want me to show you some new words, you can ask me things like '\x0313show me some random words\x03' or '\x0313send me a new word\x03'. Feel free to talk to me in natural English. You can say hi, bye, thanks or ask me what's up!"
  );

  $responses_file = fopen("venus_responses.txt", "w");
  fwrite($responses_file, json_encode($responses));
  fclose($responses_file);
?>
