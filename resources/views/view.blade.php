@extends('layouts.app')

@section('addTopCSS')
<style>
 .result.active{background:green}
</style>
@endsection


@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">View event : {{ $details->name}}</div>

                <div class="card-body">
                 @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   
                   <div class="row">
                        <div class="col-md-5">
                            <p>Created : {{ $details->created_at->diffForHumans()}}</p>
                            <p>Author : {{ Auth::user()->name}}</p>
                            <p>Start : {{ $details->start}}</p>
                            <p>End : {{ $details->end }}</p>
                            <p><a href="{{ route('home') }}"> Dashboard</a></p>
                        </div>
                        <div class="col-md-7">
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
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>


@endsection