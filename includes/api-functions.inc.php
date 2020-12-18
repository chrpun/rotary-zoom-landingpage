<?php
/*

========================================================
Rotary Club Zoom Landing Page
Copyright 2020 Christian Punke, Rotary E-Club of D-1850
https://github.com/chrpun/rotary-zoom-landingpage
========================================================

includes/api-functions.inc.php (required)
>> wird von settings.inc.pph eingebunden
>> JSON Web Token Generator Funktion
>> Verschiedenste API-Call Funktionen zu Zoom

========================================================
*/


/*
==========================================
JWT Funktion
==========================================
*/
function jwt($time=10)
{
  global $api_key, $api_secret;
  
  // Create token header as a JSON string
  $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
  // Create token payload as a JSON string (10 seconds valid)
  $payload = json_encode(['iss' => $api_key, 'exp' => time()+$time]);
  // Encode Header to Base64Url String
  $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
  // Encode Payload to Base64Url String
  $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
  // Create Signature Hash
  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $api_secret, true);
  // Encode Signature to Base64Url String
  $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
  // Create JWT
  $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
  
  return $jwt;
}

/*
==========================================
API-Call Funktion: Meeting Infos
https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meeting
==========================================
*/
function get_meeting_info($id)
{

  
  global $api_server;
  $token = jwt();
  
  $curl = curl_init();

  curl_setopt_array($curl, array(
  	CURLOPT_URL => $api_server."meetings/".$id,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "GET",
  	CURLOPT_HTTPHEADER => array(
  		"authorization: Bearer ".$token,
  		"content-type: application/json"
  	),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
  	exit("cURL Error #:" . $err);
  } else {
  	$array = json_decode($response, true);
  	if (json_last_error()) {
  		exit("JSON Error:" . json_last_error_msg());
  	} else {
      if(isset($array['code'])){
        exit("API Error #" . $array['code']. ': '. $array['message']);
      } else {
    		// $url = $array['start_url'];
    		// $status = $array['status']; //wating, started oder finished
        return $array;
      }
  		
  	}
  }
}

/*
==========================================
API-Call Funktion: Register Participant
https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingregistrantcreate
==========================================
*/
function register_participant($id, $participant_info)
{

  global $api_server;
  $token = jwt();
  
  $json = json_encode($participant_info);
  
  $curl = curl_init();

  curl_setopt_array($curl, array(
  	CURLOPT_URL => $api_server."meetings/".$id."/registrants",
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $json,
  	CURLOPT_HTTPHEADER => array(
  		"authorization: Bearer ".$token,
  		"content-type: application/json"
  	),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
  	exit("cURL Error #:" . $err);
  } else {
  	$array = json_decode($response, true);
  	if (json_last_error()) {
  		exit("JSON Error:" . json_last_error_msg());
  	} else {
      return $array;
  	}
  }
}

/*
==========================================
API-Call Funktion: List all Participants
https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingregistrants
==========================================
*/
function list_all_participants($id)
{

  global $api_server;
  $token = jwt();

  $curl = curl_init();

  curl_setopt_array($curl, array(
  	CURLOPT_URL => $api_server."meetings/".$id."/registrants",
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "GET",
  	CURLOPT_HTTPHEADER => array(
  		"authorization: Bearer ".$token,
  		"content-type: application/json"
  	),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
  	exit("cURL Error #:" . $err);
  } else {
  	$array = json_decode($response, true);
  	if (json_last_error()) {
  		exit("JSON Error:" . json_last_error_msg());
  	} else {
      return $array;
  	}
  }
}

/*
==========================================
API-Call Funktion: Get all last meeting instances
https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/pastmeetings
>> Hiermit findet man die UUID für die entsprechende Meeting IDs raus. Jedes gestartete Meeting >> eine UUID
==========================================
*/
function list_last_meetings($id)
{

  global $api_server;
  $token = jwt();

  $curl = curl_init();

  curl_setopt_array($curl, array(
  	CURLOPT_URL => $api_server."past_meetings/".$id."/instances",
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "GET",
  	CURLOPT_HTTPHEADER => array(
  		"authorization: Bearer ".$token,
  		"content-type: application/json"
  	),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
  	exit("cURL Error #:" . $err);
  } else {
  	$array = json_decode($response, true);
  	if (json_last_error()) {
  		exit("JSON Error:" . json_last_error_msg());
  	} else {
      
      /* BEISPIEL
      
      "meetings": [
        {
            "uuid": "JYFHcG/dQY+r1B/fi9EdOA==",
            "start_time": "2020-04-19T20:25:46Z"
        },
        {
            "uuid": "TdKOxW7xTCya4H4T52eCaA==",
            "start_time": "2020-04-19T20:04:50Z"
        }
      ]
      */
      
      // Das Array ist nicht nach Zeit, sondern nach UUID sortiert. Also muss es absteigend umsortiert werden.
      // dafür wird das Array umdefiniert in "$array[UUID] = Zeit (als Unix-zeit)".
      
      foreach ($array['meetings'] as $value) {
        $array_to_sort[$value['uuid']] = strtotime($value['start_time']);
      }
      arsort($array_to_sort);
      
      // und wieder in die ursprüngliche Form bringen ($array[]['uuid'] = uuid und $array[]['start_time'] = Zeit)
      
      $i = 0;
      foreach ($array_to_sort as $key => $value) {
        $array2[$i]['uuid'] = $key;
        $array2[$i]['start_time'] = $value;
        $i++;
      }
      
      return $array2; // hier kann dann durch die UUIDs gelaufen werden
  	}
  }
}


/*
==========================================
API-Call Funktion: Meeting Report
https://marketplace.zoom.us/docs/api-reference/zoom-api/reports/reportmeetingdetails
>> Hiermit findet man die genrellen Infos des entsprechenden (via UUID) Meetings heraus.
==========================================
*/
function meeting_report($uuid)
{

  global $api_server;
  $token = jwt();
  
  $uuid = urlencode(urlencode($uuid)); //Note: Please double encode your UUID when using this API if the UUID begins with a '/'or contains ‘//’ in it.

  $curl = curl_init();

  curl_setopt_array($curl, array(
  	CURLOPT_URL => $api_server."report/meetings/".$uuid,
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "GET",
  	CURLOPT_HTTPHEADER => array(
  		"authorization: Bearer ".$token,
  		"content-type: application/json"
  	),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
  	exit("cURL Error #:" . $err);
  } else {
  	$array = json_decode($response, true);
  	if (json_last_error()) {
  		exit("JSON Error:" . json_last_error_msg());
  	} else {
      
      /* BEISPIEL
      {
    "uuid": "JYFHcG/dQY+r1B/fi9EdOA==",
    "id": 587401301,
    "type": 8,
    "topic": "Register Test 1",
    "start_time": "2020-04-19T20:25:46Z",
    "end_time": "2020-04-19T20:31:26Z",
    "duration": 6,
    "total_minutes": 10,      (das sind alle Minuten aller Teilnehmer (Summe...))
    "participants_count": 5,
      }
      */
      return $array;
  	}
  }
}

/*
==========================================
API-Call Funktion: Teilnehmer Meeting Report
https://marketplace.zoom.us/docs/api-reference/zoom-api/reports/reportmeetingparticipants
>> Hiermit findet man die Infos eines jeden Teilnehmers des entsprechenden (via UUID) Meetings heraus.
==========================================
*/
function participant_meeting_report($uuid)
{

  global $api_server;
  $token = jwt();
  
  $uuid = urlencode(urlencode($uuid)); //Note: Please double encode your UUID when using this API if the UUID begins with a '/'or contains ‘//’ in it.

  $curl = curl_init();

  curl_setopt_array($curl, array(
  	CURLOPT_URL => $api_server."report/meetings/".$uuid."/participants?page_size=300",
  	CURLOPT_RETURNTRANSFER => true,
  	CURLOPT_ENCODING => "",
  	CURLOPT_MAXREDIRS => 10,
  	CURLOPT_TIMEOUT => 30,
  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  	CURLOPT_CUSTOMREQUEST => "GET",
  	CURLOPT_HTTPHEADER => array(
  		"authorization: Bearer ".$token,
  		"content-type: application/json"
  	),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
  	exit("cURL Error #:" . $err);
  } else {
  	$array = json_decode($response, true);
  	if (json_last_error()) {
  		exit("JSON Error:" . json_last_error_msg());
  	} else {
      
      /* BEISPIEL
    "page_count": 1,
    "page_size": 300,
    "total_records": 5,
    "next_page_token": "",
    "participants": [
        {
            "id": "0SW6P8sDSJymQSVH1O69Dw",
            "user_id": "16778240",
            "name": "Jan-Arne Schiller",
            "user_email": "christian.punke@rotary-eclub1850.de",
            "join_time": "2020-04-19T20:25:46Z",
            "leave_time": "2020-04-19T20:31:25Z",
            "duration": 339,
            "attentiveness_score": ""
        },
        {
            "id": "",
            "user_id": "33555456",
            "name": "Mike4 Brown4",
            "user_email": "myemail@mycompany.com",
            "join_time": "2020-04-19T20:25:58Z",
            "leave_time": "2020-04-19T20:26:07Z",
            "duration": 9,
            "attentiveness_score": ""
        },
      ...
      */
      return $array['participants']; // hier kann man dann durch alle Teilnehmer browsen
  	}
  }
}


// Kleine Helfer-Funktion um assoziative Arrays (auch rekursive) als HTML-Tabelle auszugeben.
function html_table($data = array()) {
	$text = '<table class="table-sm table-striped"><tbody>'; //table mit bootstrap-Klassen
	foreach($data as $key=>$value) {
	    if (is_array($value)) {
			$text .= "<tr>";
		    $text .= "<td>" . $key . "</td><td>" . html_table($value) . "</td>"; // rekursiver Aufruf falls der Array-Wert wieder ein Array ist
		    $text .= "</tr>";
	    } else {
			$text .= "<tr>";
		    $text .= "<td>" . $key . '</td><td style="word-break: break-all; word-break: break-word;">' . $value . "</td>"; // word-break wegen der langen URLs
		    $text .= "</tr>";
	    }
	}
	$text .= "</tbody></table>";
	
	return $text;
}

?>