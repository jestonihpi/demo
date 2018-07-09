@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Calendar</div>

                <div class="card-body">
                 @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   
                   <div class="row">
                  
                        <div class="col-md-5">
                            {!! Form::open(['route' => 'save']) !!}


                            <div class="form-group">
                                <label for="event-name">Event </label>
                                <input type="text" class="form-control" name="event_name" id="event-name" >
                            </div>
                                            
                            <div class="row">
                                <div class="col">
                                    <p>From</p>
                                    <input type="text" class="form-control datepicker-from" name="date_from" id="date-from" autocomplete="off" >
                                </div>
                                <div class="col">
                                    <p>To</p>
                                    <input type="text" class="form-control datepicker-to" name="date_to" id="date-to" autocomplete="off" >
                                </div>
                            </div>
                            <p></p>
                            <div class="form-group">
                                <p>Days</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input day-selector" type="checkbox" name="days[]"  id="monday" value="mon">
                                    <label class="form-check-label" for="monday">Mon</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input day-selector" type="checkbox" name="days[]" id="tuesday" value="tue">
                                    <label class="form-check-label" for="tuesday">Tue</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input day-selector" type="checkbox" name="days[]"  id="wednesday" value="wed">
                                    <label class="form-check-label" for="wednesday">Wed</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input day-selector" type="checkbox" name="days[]"  id="thursday" value="thu">
                                    <label class="form-check-label" for="thursday">Thu</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input day-selector" type="checkbox" name="days[]"  id="friday" value="fri">
                                    <label class="form-check-label" for="friday">Fri</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input day-selector" type="checkbox" name="days[]"  id="saturday" value="sat">
                                    <label class="form-check-label" for="saturday">Sat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input day-selector" type="checkbox" name="days[]"  id="sunday" value="sun">
                                    <label class="form-check-label" for="sunday">Sun</label>
                                </div>
                              
                            </div> 
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            
                            {!! Form::close() !!}
                            
                        </div>

                        <div class="col-md-7">
                            <div id="results" >

                                
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('addTopCSS')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
 .result.active{background:green}
</style>
@endsection

@section('addBottomJS')
<script>
$( function() {

    $( "#date-to " ).datepicker({
        dateFormat: 'yy-mm-dd' 
    } );
    
    $('#date-from').datepicker({
        dateFormat: 'yy-mm-dd' ,
        onSelect: function(date){
        var selectedDate = new Date(date);
        var msecsInADay = 86400000;
        var endDate = new Date(selectedDate.getTime() + msecsInADay);

        //Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
        $("#date-to").datepicker( "option", "minDate", endDate );
        $("#date-to").datepicker( "option", "maxDate", '+4w' );

        }
    });

    // reset selected days
    $('#date-to,#date-from').change(function(){
        $('.result').removeClass('active');   
        $('.day-selector').attr('checked', false);
        $('.result .event-name').html('');

    })
    
    // event name event   
    $('#event-name').keypress(function(){

        $('.result.active .event-name').html( ' - '+$(this).val() );
     }) 


    // Returns an array of dates between the two dates
    var getDates = function(startDate, endDate) {
    var dates = [],
        currentDate = startDate,
        addDays = function(days) {
            var date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
        };
        while (currentDate <= endDate) {
            dates.push(currentDate);
            currentDate = addDays.call(currentDate, 1);
        }
        return dates;
    };

    $('#date-to').change(function(){


    var dates = getDates(new Date($('#date-from').val()), new Date( $('#date-to').val() ));                                                                                                           
    
    dates.forEach(function(date) {
        month = "";
        if( typeof(header) === "undefined"){
                header = null;
            }
            if( header != $.format.date(date, "MMMM")){
                header = $.format.date(date, "MMMM");
            month = "<h3>"+header+"</h3>";
                
        }
        template = month + "<div class='col-lg-12 result "+ $.format.date(date, "E").toLowerCase()+"'>" +  $.format.date(date, "E") + " - "+ $.format.date(date, "dd") +" <blockqoute class='event-name blockquote pull-right'></blockqoute></div><hr>";
        $('#results').append(template);
        });

    })

    $('.day-selector').click(function(){

        if($(this).is(":checked")){
           $('div.result.'+ $(this).val() ).addClass('active');
           $('div.result.'+ $(this).val() + ' .event-name' ).html( ' - '+ $('#event-name').val() );
        }else{
            $('div.result.'+ $(this).val() ).removeClass('active');
            $('div.result.'+ $(this).val() + ' .event-name' ).html('');

        }


    });

} );
</script>
@endsection
@section('addTopJS')
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/jquery-dateformat.js') }}"></script>



@endsection
