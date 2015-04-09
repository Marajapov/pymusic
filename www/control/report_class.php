<?php

class Singer{
    var $singer;
    var $count;

    var $songs = array();
    var $radioReports = array();
}

class Song{
    var $song;
    var $count;

    var $radios = array();
}

class Radio{
    var $radio;
    var $count;

    var $radiotimes = array();
}

class RadioReports{
    var $radio;
    var $radio_id;
    var $count;
    var $radioreporttimes = array();
}

class RadioTime{
    var $date;
    var $time;
}

class RadioReportTime{
    var $date;
    var $time;
    var $song;
}

?>