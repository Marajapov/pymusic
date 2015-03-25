<?php
    include('../config.php');
    include("header.php");

    if(isset($_POST['search'])){
        $where = '';
        $select_radio = getpost('select_radio');

        $singer = getpost('signer');
        $from = getpost('from');
        $to = getpost('to');
        $from_date = date("Y-m-d",strtotime($from));
        $to_date = date("Y-m-d",strtotime($to));


        // If selected radio
        if(!empty($select_radio) && !empty($singer) && !empty($from) && !empty($to)){ // Radio and Singer and Period
            $where .= "p.radio_id='".$select_radio."' AND m.artist = '".$singer."' AND p.date_played >= '".$from_date."' AND p.date_played <='".$to_date."'";
        }elseif(!empty($singer) && !empty($from) && !empty($to)){ // Singer and Period
            $where .= " m.artist = '".$singer."' AND p.date_played >= '".$from_date."' AND p.date_played <='".$to_date."'";
        }elseif(!empty($select_radio) && !empty($from) && !empty($to)){ // Radio and Period
            $where .= " p.radio_id='".$select_radio."' AND p.date_played >= '".$from_date."' AND p.date_played <='".$to_date."'";
        }elseif(!empty($singer) && !empty($select_radio)){ // Radio and singer
            $where .= "p.radio_id='".$select_radio."' AND  m.artist = '".$singer."'";
        }elseif(!empty($select_radio)){ // Radio
            $type = 3; // For date to be hidden
            $where .= "p.radio_id='".$select_radio."' AND p.date_played >= NOW()- INTERVAL 1 DAY";
        }elseif(!empty($from) && !empty($to)){ // Period
            $where .= "p.date_played >= '".$from_date."' AND p.date_played <='".$to_date."'";
        }elseif(!empty($singer)){  // Singer
            $where .= " m.artist = '".$singer."'";
        }

        $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE
        ".$where."
        GROUP BY m.track_id
        ORDER BY number_track DESC, p.date_played DESC";


        $radio = $db->selectpuresql($sql);

    }elseif(isset($_POST['btn_today'])){
        $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played = CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

        $radio = $db->selectpuresql($sql);
    }elseif(isset($_POST['btn_3'])){
        $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played BETWEEN CURDATE()-3 AND CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

        $radio = $db->selectpuresql($sql);
    }elseif(isset($_POST['btn_week'])){
        $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played BETWEEN CURDATE()-7 AND CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

        $radio = $db->selectpuresql($sql);
    }elseif(isset($_POST['btn_14'])){
        $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played BETWEEN CURDATE()-14 AND CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

        $radio = $db->selectpuresql($sql);
    }elseif(isset($_POST['btn_month'])){
        $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played BETWEEN CURDATE()-30 AND CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

        $radio = $db->selectpuresql($sql);
    }else{
        $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played >= NOW()- INTERVAL 1 DAY
        GROUP BY m.track_id
        ORDER BY number_track DESC";

        $radio = $db->selectpuresql($sql);

    }

    include('header_menu.php');
?>
<script src="../css/bootstrap-3.3.2-dist/js/jquery-ui.js"></script>
<script src="../css/bootstrap-3.3.2-dist/js/jquery-1.11.2.js"></script>
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<!--<link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/js/ui/1.11.4/jquery-ui.css">-->
<!--<script src="../css/bootstrap-3.3.2-dist/js/ui/1.11.4/jquery-ui.js"></script>-->
<script>
    $(function() {
        $( "#from" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>

<div class="container-fluid">


<?php
    /*$result = $db->select_one("played_melody","date_played=CURDATE();");
    $result['radio'];*/
?>
    <div class="row" style="margin: 0 auto; position: relative;">
        <div class="col-lg-offset-2 col-lg-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="margin-left: 41px">Выберите радио</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="showStatisticsAboutRadio.php">
                        <div class="form-group col-md-12">
                            <div class="col-md-6 no-padding">
                                <select required="required" class="form-control" name="select_radio">
                                    <option value="0"> ... Выберите ... </option>
                                    <?php
                                    $radio_type = $db->select('radio');
                                    foreach($radio_type as $radio_type_row){
                                        echo '<option value="'.$radio_type_row['id'].'">'.$radio_type_row['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-12">

                            <div class="col-md-6 no-padding">

                                <div class="col-md-5 no-padding">
                                    <label for="from">from&nbsp;</label>
                                    <input type="text" id="from" class="from form-control" name="from">
                                </div>

                                <div class="col-md-offset-2 col-md-5 no-padding">
                                    <label for="to">&nbsp;to&nbsp;</label>
                                    <input type="text" id="to" class="to form-control" name="to">
                                </div>

                            </div>

                        </div>

                        <script>
                            (function( $ ) {
                                $.widget( "custom.combobox", {
                                    _create: function() {
                                        this.wrapper = $( "<span>" )
                                            .addClass( "custom-combobox" )
                                            .insertAfter( this.element );

                                        this.element.hide();
                                        this._createAutocomplete();
                                        this._createShowAllButton();
                                    },

                                    _createAutocomplete: function() {
                                        var selected = this.element.children( ":selected" ),
                                            value = selected.val() ? selected.text() : "";

                                        this.input = $( "<input>" )
                                            .appendTo( this.wrapper )
                                            .val( value )
                                            .attr( "title", "" )
                                            .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                                            .autocomplete({
                                                delay: 0,
                                                minLength: 0,
                                                source: $.proxy( this, "_source" )
                                            })
                                            .tooltip({
                                                tooltipClass: "ui-state-highlight"
                                            });

                                        this._on( this.input, {
                                            autocompleteselect: function( event, ui ) {
                                                ui.item.option.selected = true;
                                                this._trigger( "select", event, {
                                                    item: ui.item.option
                                                });
                                            },

                                            autocompletechange: "_removeIfInvalid"
                                        });
                                    },

                                    _createShowAllButton: function() {
                                        var input = this.input,
                                            wasOpen = false;

                                        $( "<a>" )
                                            .attr( "tabIndex", -1 )
                                            .attr( "title", "Show All Items" )
                                            .tooltip()
                                            .appendTo( this.wrapper )
                                            .button({
                                                icons: {
                                                    primary: "ui-icon-triangle-1-s"
                                                },
                                                text: false
                                            })
                                            .removeClass( "ui-corner-all" )
                                            .addClass( "custom-combobox-toggle ui-corner-right" )
                                            .mousedown(function() {
                                                wasOpen = input.autocomplete( "widget" ).is( ":visible" );
                                            })
                                            .click(function() {
                                                input.focus();

                                                // Close if already visible
                                                if ( wasOpen ) {
                                                    return;
                                                }

                                                // Pass empty string as value to search for, displaying all results
                                                input.autocomplete( "search", "" );
                                            });
                                    },

                                    _source: function( request, response ) {
                                        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                                        response( this.element.children( "option" ).map(function() {
                                            var text = $( this ).text();
                                            if ( this.value && ( !request.term || matcher.test(text) ) )
                                                return {
                                                    label: text,
                                                    value: text,
                                                    option: this
                                                };
                                        }) );
                                    },

                                    _removeIfInvalid: function( event, ui ) {

                                        // Selected an item, nothing to do
                                        if ( ui.item ) {
                                            return;
                                        }

                                        // Search for a match (case-insensitive)
                                        var value = this.input.val(),
                                            valueLowerCase = value.toLowerCase(),
                                            valid = false;
                                        this.element.children( "option" ).each(function() {
                                            if ( $( this ).text().toLowerCase() === valueLowerCase ) {
                                                this.selected = valid = true;
                                                return false;
                                            }
                                        });

                                        // Found a match, nothing to do
                                        if ( valid ) {
                                            return;
                                        }

                                        // Remove invalid value
                                        this.input
                                            .val( "" )
                                            .attr( "title", value + " didn't match any item" )
                                            .tooltip( "open" );
                                        this.element.val( "" );
                                        this._delay(function() {
                                            this.input.tooltip( "close" ).attr( "title", "" );
                                        }, 2500 );
                                        this.input.autocomplete( "instance" ).term = "";
                                    },

                                    _destroy: function() {
                                        this.wrapper.remove();
                                        this.element.show();
                                    }
                                });
                            })( jQuery );

                            $(function() {
                                $( "#combobox" ).combobox();
                                $( "#toggle" ).click(function() {
                                    $( "#combobox" ).toggle();
                                });
                            });
                        </script>

                        <div class="form-group col-md-12">
                            <div class="col-md-6 no-padding">
                                <div class="ui-widget">
                                    <select id="combobox" name="signer">
                                        <option value="">Select one...</option>
                                        <?php
                                        $artist = $db->select("artist");
                                        foreach($artist as $art){
                                        ?>
                                        <option value="<?php echo $art['name']; ?>"><?php echo $art['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>



                        </div>

                        <div class="form-group col-md-12">
                            <input class="btn btn-default" type="submit" name="btn_today" value="Today"/>
                            <input class="btn btn-primary" type="submit" name="btn_3" value="3"/>
                            <input class="btn btn-success" type="submit" name="btn_week" value="week"/>
                            <input class="btn btn-warning" type="submit" name="btn_14" value="14"/>
                            <input class="btn btn-danger" type="submit" name="btn_month" value="Month"/>
                        </div>

                        <div class="form-group col-md-12">
                            <input type="submit" value="Search" name="search">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ------------------------------------------------ Result -------------------------------------------- -->

    <div class="table">
        <table class="table table-striped table-bordered table-hover" style="width: 65%; margin: 25px auto;">
            <thead>
                <th>№</th>
                <th>Певец</th>
                <th>Песня</th>
                <th>Кол-во воспроизведений</th>
            </thead>
            <tbody>

            <?php
            foreach($radio as $key=>$radio_row){
                $key++;
                ?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $radio_row['artist']; ?></td>
                    <td><?php echo $radio_row['song']; ?></td>
                    <td><a href="#" data-toggle="modal" data-target="#myModal<?=$key;?>"><?=$radio_row['number_track'];?></a></td>



                </tr>
                <div class="modal fade" id="myModal<?=$key;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Информация</h4>
                            </div>
                            <div class="modal-body">
                                <?php
                                $where_date = '';
                                if(!empty($date_selected)){

                                }else{
                                    $where_date .= '';
                                }
                                echo $query = "
                                SELECT artist,song,radio,count(*) c
                                FROM played_melody pm,melody m
                                WHERE m.track_id=pm.track_id
                                AND pm.track_id = '".$radio_row['p_track_id']."' AND
                                '".$where_date."'
                                GROUP BY artist,song,radio
                                ORDER BY artist,song,c desc;";

                                $radio_list = $db->selectpuresql($query);


                                foreach($radio_list as $list){
                                    echo $list['radio']." - ".$list['c']."<br />";
                                }
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }

            /*
             *   if(isset($select_radio)){
            $where .= "p.radio_id = ".$select_radio;
        }elseif(isset($from) && isset($to)){
            $where .= 'AND p.date_played >= '.$from.' and p.date_played <= '.$to;
        }
        elseif(isset($singer)){
            $singers = $db->select_one("artist","id='".$singer."'");
            $singer_name = $singers['name'];
            $where .= 'AND m.artist = '.$singer_name;
        }

        echo $sql = "
        SELECT  m.artist, m.song,
                p.date_played, p.time_played,
                r.name, COUNT(m.song) as c_song
        FROM played_melody p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE ".$where."
        ORDER BY p.date_played DESC"
             * */
            ?>
            </tbody>
        </table>

    </div>
