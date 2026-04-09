@extends('front.layouts.app')
@section('title') {{$page->title}}  @endsection
 
@section('css')
 <style type="text/css">
     .shape {
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            z-index: 1;
            pointer-events: none;
        }
        .shape>svg {
            width: 100%;
            height: auto;
            fill: #f3f3f9;
        }
        img{
            width:100%;
        }
 </style>
@endsection
@section('content')

<div class="page__content">
<section class="section">
<div class="container">


<div class="row justify-content-center mt-4">
                     {{-- <div>
                       <h3>{!! $page->title !!}</h3> 
                    </div> --}}
                    <div>
                        {!! $page->details !!}
                    </div>
                </div>

</div>
</section>
</div>

@endsection