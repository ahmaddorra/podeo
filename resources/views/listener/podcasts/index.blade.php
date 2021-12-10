@extends('layouts.app')
@push('css')
<style>
    @-webkit-keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    .stroke-dotted {
        opacity: 0;
        stroke-dasharray: 4,5;
        stroke-width: 1px;
        transform-origin: 50% 50%;
        -webkit-animation: spin 4s infinite linear;
        animation: spin 4s infinite linear;
        transition: opacity 1s ease,  stroke-width 1s ease;
    }

    .stroke-solid {
        stroke-dashoffset: 0;
        stroke-dashArray: 300;
        stroke-width: 4px;
        transition: stroke-dashoffset 1s ease,  opacity 1s ease;
    }

    .icon {
        transform-origin: 50% 50%;
        transition: transform 200ms ease-out;
    }

    #play:hover .stroke-dotted {
        stroke-width: 4px;
        opacity: 1;
    }
    #play:hover .stroke-solid {
        opacity: 0;
        stroke-dashoffset: 300;
    }
    #play:hover .icon {
        transform: scale(1.05);
    }

    #play {
        cursor: pointer;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateY(-50%) translateX(-50%);
    }


</style>
@endpush
@section('content')
<div class="container-fluid">
    @if(count($categories) == 0)
        <h3>No podcasts yet. Come back later</h3>
    @endif
    @foreach($categories as $category)
    <section class="projects-section">
        <header class="projects-header">
            <h2>{{$category->name}} <br/>Projects</h2>
        </header>
        <div class="projects-grid w-100">
            @foreach($category->podcasts as $podcast)
            <div class="project">
                <h3 class="title">{{$podcast->name}}</h3>
                <div class="description">
                    <p>
                        {{$podcast->description}}
                    </p>
                </div>
                <div><img width="270" height="155" loading="lazy" src="{{asset('public/storage/'.$podcast->image)}}" alt=""/></div>
                <a  href="{{route('listener.podcasts.show', $podcast->id)}}" hreflang="en">
                    <svg version="1.1" id="play" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" height="100px" width="100px"
                         viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
  <path class="stroke-solid" fill="none" stroke="white"  d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7
    C97.3,23.7,75.7,2.3,49.9,2.5"/>
                        <path class="stroke-dotted" fill="none" stroke="white"  d="M49.9,2.5C23.6,2.8,2.1,24.4,2.5,50.4C2.9,76.5,24.7,98,50.3,97.5c26.4-0.6,47.4-21.8,47.2-47.7
    C97.3,23.7,75.7,2.3,49.9,2.5"/>
                        <path class="icon" fill="white" d="M38,69c-1,0.5-1.8,0-1.8-1.1V32.1c0-1.1,0.8-1.6,1.8-1.1l34,18c1,0.5,1,1.4,0,1.9L38,69z"/>
</svg>
                </a>
            </div>
            @endforeach
        </div>
    </section>
        @endforeach

</div>
@endsection
