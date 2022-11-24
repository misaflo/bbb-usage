<?php

use BigBlueButton\BigBlueButton;

define ("BBB_USAGE_VERSION", "1.1");

function getCurrentData ($show_server = "%")
{
    require_once './vendor/autoload.php';
    require_once 'conf.php';

    $data = array ();
    
    if(file_exists('servers.yml')) {
        $serverfile = yaml_parse_file('servers.yml');
//        var_dump($servers);
        
        foreach($serverfile['servers'] as $serverkey => $conf) {
            
            $bbb_secret = $conf['bbb_secret'];
            $servername = $conf['servername'];
            $server = $serverkey;
            
            $data = array_merge($data, getData($show_server,$bbb_secret,$servername, $server));
        }

    } else {
        global $bbb_secret , $servername;
        $data = getData($show_server,$bbb_secret, $servername);
    }

    return $data;
    
}

function getData($show_server,$bbb_secret,$servername, $serverconf=null) {

    putenv ("BBB_SECRET=$bbb_secret");
    putenv ("BBB_SERVER_BASE_URL=$servername/bigbluebutton/");

    $bbb = new BigBlueButton();

    $response = $bbb->getMeetings();

    $data =  array();

    if ($response->getReturnCode() == 'SUCCESS') {
        $content = $response->getRawXml();
        foreach ($content->meetings->meeting as $meeting) {
            // process all meetings and save usage data to array (by server)
            
            if(META_SERVER === true && isset($meeting->metadata->{'bbb-origin-server-name'})) {
                $server = (string)$meeting->metadata->{'bbb-origin-server-name'};
            } else {
                $server = $serverconf;
            }

            if (($show_server == "%") or ($show_server == $server))
            {
                if (!array_key_exists($server, $data))
                {
                    // new server - init stats (to avoid php warning undefined index)
                    $data [$server]['meeting_count'] = 0;
                    $data [$server]['participant_count'] = 0;
                    $data [$server]['voice_participant_count'] = 0;
                    $data [$server]['video_count'] = 0;
                    $data [$server]['breakout_count'] = 0;
                    $data [$server]['server_phys'] = $servername;
                }

                $data [$server]['meeting_count'] += 1;
                $data [$server]['participant_count'] += $meeting->participantCount;
                $data [$server]['voice_participant_count'] += $meeting->voiceParticipantCount + $meeting->listenerCount;
                $data [$server]['video_count'] += $meeting->videoCount;
                $data [$server]['server_phys'] = $servername;
                if ((string)$meeting->isBreakout == "true")
                {
                    $data [$server]['breakout_count'] += 1;
                }
            }
        }
    }
    return $data;
}