<?php

  /*TO DO
    save words
    word contexts
    word synonyms
    word examples
    word quizzes and reviews
  */

  include("my_api_key.php");

  $responses_file = fopen("bot_responses.txt", "r");
  $responses = json_decode(fread($responses_file, filesize("bot_responses.txt")));


  function random_greeting() {

    $greetings = $GLOBALS['responses']->greetings;
    $exclamation = $GLOBALS['responses']->exclamation;
    $smileys = $GLOBALS['responses']->smileys;

    return $greetings[mt_rand(0, count($greetings) - 1)].$exclamation[mt_rand(0, count($exclamation) - 1)]." ".$smileys[mt_rand(0, count($smileys) - 1)]."\n";
  }

  function random_ty_response() {
    $ty_responses = $GLOBALS['responses']->ty_responses;

    return $ty_responses[mt_rand(0, count($ty_responses) - 1)]."\n";
  }

  function random_unknownmsg_response() {
    $unknownmsg_responses = $GLOBALS['responses']->unknownmsg_responses;

    return $unknownmsg_responses[mt_rand(0, count($unknownmsg_responses) - 1)]."\n";
  }

  function help() {
    $helpmsg = $GLOBALS['responses']->helpmsg;
    return $helpmsg."\n";
  }

  function wordnik($word) {
    global $my_api_key;

    $word_obj = json_decode(file_get_contents("http://api.wordnik.com:80/v4/word.json/$word/definitions?limit=5&includeRelated=true&sourceDictionaries=all&useCanonical=true&includeTags=false&api_key=".$my_api_key));
    $definition = "Definitions for \x0310".strtolower($word)."\x03 - Here you go!\n";
    for ($i = 0; $i < count($word_obj); $i++) {
      $definition .= "\x039".$word_obj[$i]->partOfSpeech." - \x03".$word_obj[$i]->text."\n";
    }
    return $definition;
  }

  function random_whatsup() {
    $whatsup_replies = $GLOBALS['responses']->whatsup_replies;

    return $whatsup_replies[mt_rand(0, count($whatsup_replies) - 1)]."\n";
  }

  function random_bye_response() {
    $bye_responses = $GLOBALS['responses']->bye_responses;
    $smileys = $GLOBALS['responses']->smileys;

    return $bye_responses[mt_rand(0, count($bye_responses) - 1)]."\n";//." ".$smileys[mt_rand(0, count($smileys) - 1)]."\n";
  }

  function random_word() {
    global $my_api_key;

    $word_obj = json_decode(file_get_contents("http://api.wordnik.com:80/v4/words.json/randomWord?hasDictionaryDef=true&includePartOfSpeech=noun,adjective,verb,adverb,past-participle,verb-transitive,verb-intransitive,noun-plural&minCorpusCount=0&excludePartOfSpeech=interjection,pronoun,preposition,abbreviation,affix,article,auxiliary-verb,conjunction,definite-article,family-name,given-name,idiom,imperative,noun-posessive,proper-noun,proper-noun-plural,proper-noun-posessive,suffix&maxCorpusCount=-1&minDictionaryCount=1&maxDictionaryCount=-1&minLength=1&maxLength=-1&limit=10&api_key=".$my_api_key));

    $random_word = $word_obj->word;
    return "\x037".$random_word."\x03"."\n";
  }

  function random_words() {
    global $my_api_key;

    $word_objs = json_decode(file_get_contents("http://api.wordnik.com:80/v4/words.json/randomWords?hasDictionaryDef=true&includePartOfSpeech=noun,adjective,verb,adverb,past-participle,verb-transitive,verb-intransitive,noun-plural&minCorpusCount=0&excludePartOfSpeech=interjection,pronoun,preposition,abbreviation,affix,article,auxiliary-verb,conjunction,definite-article,family-name,given-name,idiom,imperative,noun-posessive,proper-noun,proper-noun-plural,proper-noun-posessive,suffix&maxCorpusCount=-1&minDictionaryCount=1&maxDictionaryCount=-1&minLength=1&maxLength=-1&limit=10&api_key=".$my_api_key));

    for($i = 0; $i < count($word_objs); $i++) {
      $random_words .= "\x037".$word_objs[$i]->word."\x03".", ";
    }
    return rtrim(trim($random_words), ',')."\n";
  }

  function smart_reply($msg) {

    preg_match('/define\s*([a-z\-]*)\b.*/i', $msg, $match);
    if($match[1])
      return wordnik($match[1]);

    preg_match('/.*\s([a-zA-Z]*)\smeans?.*/i', $msg, $match);
    if($match[1])
      return wordnik($match[1]);



    // preg_match('/what\'?s?\s*(is\s*)?a?n?\s+([a-z\-]*)\b.*/i', $msg, $match);
    preg_match('/^\s*w.*\s([a-z\-]+)[\W]*$/i', $msg, $match);
    if($match[1]) {
      if(strtolower($match[1]) == "up" || strtolower($match[1]) == "doing" || strtolower($match[1]) == "doin")
        return random_whatsup();

      return wordnik($match[1]);
    }

    preg_match('/^\s*help\s*$/i', $msg, $match);
    if($match[0]) {
        return help();
    }

    preg_match('/^([a-z\-]+)\?$/i', $msg, $match);
    if($match[1]) {
        return wordnik($match[1]);
    }

    preg_match('/\bs+u+p+\b/i', $msg, $match);
    if($match[0]) {
      return random_whatsup();
    }

    preg_match('/\bhe+y+\b|\bhe+l+o+\b|\bhi+\b|\byo+\b|\bbo+\b/i', $msg, $match);
    if($match) {
      $msg = preg_replace('/\bhe+y+\b|\bhe+l+o+\b|\bhi+\b|\byo+\b|\bbo+\b/i', '', $msg);
      echo $msg;
      return random_greeting()."\n".smart_reply($msg);
    }

    preg_match('/.*(random|new)?\s+(words?)/i', $msg, $match);
    if($match[0]) {
      if(strtolower($match[2]) == "word")
        return random_word();

      else if(strtolower($match[2]) == "words")
        return random_words();
    }

    preg_match('/tha+nks*|ty|thx+/i', $msg, $match);
    if($match[0])
      return random_ty_response();

    preg_match('/\b(go+d+)?\s*by+e+\b|(se+|c)\s*(yo*)?u+\b|\bc\s*y+a+\b|\bbi+\b|\bla+te+r+\b|\bl8r+\b|\btty|\bci+a+o+\b/i', $msg, $match);
    if($match[0])
      return random_bye_response();



  }


  /*IRC vocab bot

  */

  $server = 'irc.mibbit.net';
  $port = 6667;
  $nick = 'VocabBot';
  $ident = 'vbot';
  $gecos = 'VocabBot v1.0';
  // $channel = '#botters-test';

  date_default_timezone_set("UTC");

  $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

  if(!socket_connect($socket, $server, $port))
    echo "Socket error ".socket_last_error()." occurred - ".socket_strerror(socket_last_error())."\n";
  else
    echo "Socket connected!\n";

  socket_write($socket, "NICK $nick\r\n");
  socket_write($socket, "USER $ident * 8 :$gecos\r\n");

  while(is_resource($socket)) {

    $data = trim(socket_read($socket, 1024, PHP_NORMAL_READ));
    echo $data;

    $d = explode(' ', $data);
    $d = array_pad($d, 10, '');

    if($d[0] === 'PING') {
      echo " received at ".date("H:i:s")."\tPONG!";
      socket_write($socket, 'PONG '.$d[1]."\r\n");
    }

    if($d[1] == 376 || $d[1] == 422) {
      socket_write($socket, 'JOIN '.$channel."\r\n");
    }

    if($d[1] == "PRIVMSG") {
      //extract username from PRIVMSG command
      preg_match('/\:(.*)\!/', $d[0], $match);
      $username = $match[1];

      //extract message from PRIVMSG command
      preg_match('/VocabBot\s\:(.*)/', $data, $match);
      $message = $match[1];

      $response = smart_reply($message);

      $responses_to_send = explode("\n", $response);

      $log_file = fopen("logs/".$username.".txt", "a");

      fwrite($log_file, "\n[".date("M d, 'y @ H:i:s")."] "."[$username]"." - ".$message);

      for($i = 0; $i < count($responses_to_send) - 1; $i++) {
        socket_write($socket, 'PRIVMSG '.$username." :"."$responses_to_send[$i]"."\r\n");
        fwrite($log_file, "\n[VocabBot] - ".$responses_to_send[$i]);
      }


    }

    echo "\n";
  }

?>
