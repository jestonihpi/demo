@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Calendar</div>

                <div class="card-body">
                    <div class="alert alert-success " style="display:none" id="success-alert" role="alert">
                        Event saved!
                    </div>
                     @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                   
                   <div class="row">
                  
                        <div class="col-md-5">
                            {!! Form::open(['route' => 'save', 'id' => 'event-form']) !!}


                            <div class="form-group">
                                <label for="event-name">Event </label>
                                <input type="text" class="form-control" name="event_name" id="event-name"   autocomplete="off">
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

                            <hr>                           
                            
                            {!! Form::close() !!}

                            <div>
                                <ul class="list-group" id="event-listing">
                                    @foreach($events as $event)
                                    <li class="list-group-item"> <a href="{{ route('view' , $event->id)}}" class="view-modal">{{ $event->name}} - {{$event->created_at->diffForHumans()}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            
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


<!-- Modal -->
<div class="modal fade" id="event-modal" tabindex="-1" role="dialog" aria-labelledby="event-modal" aria-hidden="true">
  <div class="modal-dialog" role="">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="event-modal-title"> -- </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         ---
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('addTopCSS')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
 .result.active{background:#28c328}
</style>
@endsection

@section('addBottomJS')
<script>
$( function() {

    //datepicker initialization and configs.

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



    // form ajax

    $('#event-form').submit(function(e){

      e.preventDefault();
        _data = $(this).serializeArray();
        $.ajax({
                type: "POST",
                url:'{{ route('save')}}',
                data: _data,
                success: function( msg ) {
                    $('#event-listing').prepend("<li class='list-group-item' >"+msg.prepend_event_html+'</li>');
                    //reset form and results once done
                    $('#results').html('');
                    $('#event-form')[0].reset();

                    $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                        $("#success-alert").slideUp(500);
                    });
                }
            });

    });

    $(document).on('click','.view-modal', function(e){
        //reset
        e.preventDefault();
        link = $(this);
       
        $('#event-modal #event-modal-title').html('---');
        $('#event-modal .modal-body').html('---');

        $.ajax({
            type: "GET",
            url: $(link).attr('href'),
            success: function( result ) {

                $('#event-modal #event-modal-title').html(result.title);
                $('#event-modal .modal-body').html(result.html);
                $('#event-modal').modal('show');
            }
        });

    })

} );
</script>
@endsection
@section('addTopJS')
<script src="{{ asset('js/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jquery-dateformat.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>



@endsection
