</div>
<!-- /#wrapper -->


<!-- Metis Menu Plugin JavaScript -->
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

<!-- DataTables JavaScript -->
<script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>

<!-- Include JS files. -->
<script src="froala-js/froala_editor.min.js"></script>

<!-- Include IE8 JS. -->
<!--[if lt IE 9]>
<script src="froala-js/froala_editor_ie8.min.js"></script>
<![endif]-->

<script>
    $(function() {
        $('div#edit').editable({
            inlineMode: false
        });
        $('div#edit1').editable({
            inlineMode: false
        })
    });
</script>


<script>
    $(function() {
        $( "#from" ).datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function( selectedDate ) {
                $( "#to" ).datepicker( "option", "minDate", selectedDate );
            }

        });
        $( "#to" ).datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            onClose: function( selectedDate ) {
                $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>

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


<script type="text/javascript" src="../css/bootstrap-3.3.2-dist/js/date.js"></script>

<script>
    var today = Date.today().toString("yyyy/MM/dd");
    var three = Date.today().addDays(-3).toString("yyyy/MM/dd");
    var week = Date.today().addWeeks(-1).toString("yyyy/MM/dd");
    var month = Date.today().addMonths(-1).toString("yyyy/MM/dd");
    var tab_1 = 1;
    var tab_2 = 2;

    function today_button_clicked(){
        $('#from').val(today);
        $('#to').val(today);
        $('#btn_1').val(1);
        $("form").submit();

    }
    function three_button_clicked(){
        $('#from').val(three);
        $('#to').val(today);
        $('#btn_2').val(2);
        $("form").submit();

    }
    function week_button_clicked(){
        $('#from').val(week);
        $('#to').val(today);
        $('#btn_3').val(3);
        $("form").submit();
    }
    function month_button_clicked(){
        $('#from').val(month);
        $('#to').val(today);
        $('#btn_4').val(4);
        $("form").submit();
    }
    function tab1_clicked(){
        $('#last_tab1').val(tab_1);
    }
    function tab2_clicked(){
        $('#last_tab2').val(tab_2);
    }



</script>


</body>

</html>