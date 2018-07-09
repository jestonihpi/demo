


@section('content')


<div id="results" >

    @foreach ($period as $dt)
        <?php
        $month = "";

        if(!isset($header)){
            $header = NULL;
        }        
        if($header != $dt->format("F") ){
            $header = $dt->format("F");
            $month = "<h3>".$header."</h3>";
        }
        
        
        $active = in_array( strtolower( $dt->format("D") ) ,$selected_days) ? true : false;
        
        ?>
        {!! $month !!}
        <div class="col-lg-12 result @if($active) active @endif">{{ $dt->format("D - d") }} 
        @if($active)
                - <blockqoute class='event-name blockquote pull-right'>
                {{  $details->name }}
            </blockqoute>
        @endif
        </div><hr>
    @endforeach
    
</div>


@endsection