<?php

$user_id = $item['id'];
$my_pure_sql = "SELECT s.name as singer_name, t.from_date, t.to_date, t.is_always FROM  user_tie  `t`
INNER JOIN  artist  `s` ON t.singer_id = s.id where t.user_id = '".$user_id."'";
$user_tie = $db->selectpuresql($my_pure_sql);



?>

<!--<link rel="stylesheet" href="../css/style.css">-->



<script>
    $(function() {
        $( "#from<?php echo $user_id; ?>" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#to<?php echo $user_id; ?>" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#to<?php echo $user_id; ?>" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#from<?php echo $user_id; ?>" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>

<div id="mainform<?php echo $user_id; ?>" style="background: #cccccc; padding-bottom: 15px;">

    <div id="form<?php echo $user_id; ?>">
        <table class="table table-hover tex" style="width: 50%; float: left; margin: 25px 25px; color:black;">
            <thead class="text-capitalize">
            <th> Радио &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </th>
            <th> Все исполнители &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
            <th> Выберите дату для исполнителя </th>

            </thead>

            <tbody class="table">
            <tr>
                <td>
                    <select id="radio<?php echo $user_id; ?>" name="radio" class="form-control">
                        <option value="">Выберите ...</option>
                        <?php
                        $radios = $db->select("radio");?>

                        <?php   foreach($radios as $radio){
                            ?>
                            <option value="<?php echo $radio['id']; ?>"><?php echo $radio['name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td class="col-md-3">

                    <div class="ui-widget">
                        <select class="combobox" id="singer<?php echo $user_id; ?>" name="singer" class="form-control">
                            <option value=""></option>
                            <?php
                            $artist = $db->select("artist");
                            foreach($artist as $art){?>
                            <option value="<?php echo $art['id']; ?>"><?php echo $art['name']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </td>

                <td class="col-md-9">

                    <div class="col-md-6">

                        <label for="from" id="fromlabel<?php echo $user_id; ?>">С&nbsp;</label>
                        <input type="text" id="from<?php echo $user_id; ?>" class="from" name="from">
                        <label for="to" id="tolabel<?php echo $user_id; ?>">&nbsp;По&nbsp;</label>
                        <input type="text" id="to<?php echo $user_id; ?>" class="to" name="to">
                        <br/><label for="lifetime_flag<?php echo $user_id; ?>">&nbsp;Постоянно:&nbsp;</label>
                        <input style="margin-left: 10px;" type="checkbox" class="lifetime_flag" id="lifetime_flag<?php echo $user_id; ?>" name="lifetime_flag" value="0">


                    </div>
                    <input type="hidden" name="user" id="user" value="<?php  echo $user_id; ?>"/>

                </td>

            </tr>
            </tbody>
        </table>
        <div style="clear: both"></div>
        <div class="col-md-offset-5 no-padding">
            <input type="button" id="submit<?php echo $user_id; ?>" class="btn btn-primary" value="Сохранить">
        </div>
    </div>
</div>

<div id="joogazin<?php echo $user_id; ?>">

    <?php
    $result =   '<br><br><br><br>
            <table class="table table-hover tex" style="width: 40%; float: right; margin: -353px 33px; margin-bottom: 5px; color: black;">
            <thead class="text-capitalize">
            <th>Singer</th>
            <th>from date</th>
            <th>to date</th>
            </thead>
            <tbody class="table">';


    foreach($user_tie as $tie_row){

        $result .= '<tr>';
        $result .= '<td class="col-md-1">'.$tie_row['singer_name'].'</td>';
        if (!$tie_row['is_always']) {
            $result .= '<td class="col-md-1">'.$tie_row["from_date"].'</td>';
            $result .= '<td class="col-md-1">'.$tie_row['to_date'].'</td>';
        } else {
            $result .= '<td class="col-md-1" colspan="2">always</td>';
        }
        $result .= '</tr>';
    }
    $result .= "</tbody> </table>";
    echo $result;

    ?>

</div>


<script>

    $(document).ready(function(){

        $("#lifetime_flag<?php echo $user_id; ?>").click(function() {
            $( "#to<?php echo $user_id; ?>" ).show();
            $( "#from<?php echo $user_id; ?>" ).show();
            $( "#tolabel<?php echo $user_id; ?>" ).show();
            $( "#fromlabel<?php echo $user_id; ?>" ).show();
            if($("#lifetime_flag<?php echo $user_id; ?>").is(':checked')) {
                $( "#to<?php echo $user_id; ?>" ).hide();
                $( "#from<?php echo $user_id; ?>" ).hide();
                $( "#tolabel<?php echo $user_id; ?>" ).hide();
                $( "#fromlabel<?php echo $user_id; ?>" ).hide();
            }
        });

        $("#submit<?php echo $user_id; ?>").click(function(){
            var user = <?php echo $user_id; ?>;
            var singer = $("#singer<?php echo $user_id; ?>").val();
            var radio = $("#radio<?php echo $user_id; ?>").val();
            var from = $("#from<?php echo $user_id; ?>").val();
            var to = $("#to<?php echo $user_id; ?>").val();
            var lifetime_flag = 0;
            if($("#lifetime_flag<?php echo $user_id; ?>").is(':checked')) lifetime_flag = 1;

// Returns successful data submission message when the entered information is stored in database.
            var dataString = 'user='+ user + '&singer='+ singer + '&from='+ from + '&to='+ to + '&lifetime_flag='+ lifetime_flag+"&radio="+radio;
            if(singer==0)
            {
                alert("Please Fill All Fields");
            }
            else
            {
                alert(dataString);
// AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "ajaxsubmit.php",
                    data: dataString,
                    cache: false,
                    success: function(result){
                        function refresh_joogazin(){

                            $('#joogazin<?php echo $user_id; ?>').html(result);
                        }
                        refresh_joogazin();
                        $("#singer<?php echo $user_id; ?>").val(0);
                        $("#radio<?php echo $user_id; ?>").val(0);
                        $("#from<?php echo $user_id; ?>").val("");
                        $("#to<?php echo $user_id; ?>").val("");
                        $("#lifetime_flag<?php echo $user_id; ?>").attr('checked', false);
                    }
                });
            }
            return false;
        });
    });

</script>


<link rel="stylesheet" href="js/jquery-ui.css">
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui.js"></script>
<style>
    .custom-combobox {
        position: relative;
        display: inline-block;
    }
    .custom-combobox-toggle {
        position: absolute;
        top: 0;
        bottom: 0;
        margin-left: -1px;
        padding: 0;
    }
    .custom-combobox-input {
        margin: 0;
        padding: 5px 10px;
    }
</style>
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
        $( ".combobox" ).combobox();

    });
</script>



