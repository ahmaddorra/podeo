@extends('layouts.app')
@push('css')
    <link href="{{ asset('public/css/listener/podcast.css') }}" rel="stylesheet">
    <link href="https://cdn.plyr.io/3.6.2/plyr.css" rel="stylesheet">
    <style>
        body{
            background: #1D2030;
        }
    </style>
@endpush
@section('content')
    <div class="container" id="podcast-container">
        <div class="column add-bottom">
            <div id="mainwrap">
                <div id="nowPlay">
                    <span id="npAction">Paused...</span><span id="npTitle"></span>
                </div>
                <div id="audiowrap">
                    <div id="audio0">
                        <audio id="audio1" preload controls>Your browser does not support HTML5 Audio! 😢</audio>
                    </div>
                    @if(count($podcast->episodes) == 0)
                        <p style="color: #fff">No episodes. Come back later!</p>
                    @endif
                    <div id="tracks">
                        <a id="btnPrev">&larr;</a><a id="btnNext">&rarr;</a>
                    </div>
                </div>
                <div id="plwrap">
                    <ul id="plList"></ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5media/1.1.8/html5media.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/plyr/3.6.8/plyr.min.js" ></script>
    <script>
        jQuery(function ($) {
            'use strict'
            var supportsAudio = !!document.createElement('audio').canPlayType;
            if (supportsAudio) {
                // initialize plyr
                var player = new Plyr('#audio1', {
                    controls: [
                        'restart',
                        'play',
                        'progress',
                        'current-time',
                        'duration',
                        'mute',
                        'volume',
                    ]
                });
                // initialize playlist and controls
                var index = 0,
                    playing = false,
                    mediaPath = '',
                    extension = '',
                    tracks = [
                        @foreach($podcast->episodes as $episode)
                        {
                            'track': "{{$loop->iteration}}",
                            'name' : "{{$episode->name}}",
                            "duration":"{{$episode->duration}}",
                            "file": "{{asset('storage/'.$episode->audio)}}"
                        },
                        @endforeach
                        ],
                    buildPlaylist = $.each(tracks, function(key, value) {
                        var trackNumber = value.track,
                            trackName = value.name,
                            trackDuration = value.duration;
                        if (trackNumber.toString().length === 1) {
                            trackNumber = '0' + trackNumber;
                        }
                        $('#plList').append('<li> \
                    <div class="plItem"> \
                        <span class="plNum">' + trackNumber + '.</span> \
                        <span class="plTitle">' + trackName + '</span> \
                        <span class="plLength">' + trackDuration + '</span> \
                    </div> \
                </li>');
                    }),
                    trackCount = tracks.length,
                    npAction = $('#npAction'),
                    npTitle = $('#npTitle'),
                    audio = $('#audio1').on('play', function () {
                        playing = true;
                        npAction.text('Now Playing...');
                    }).on('pause', function () {
                        playing = false;
                        npAction.text('Paused...');
                    }).on('ended', function () {
                        npAction.text('Paused...');
                        if ((index + 1) < trackCount) {
                            index++;
                            loadTrack(index);
                            audio.play();
                        } else {
                            audio.pause();
                            index = 0;
                            loadTrack(index);
                        }
                    }).get(0),
                    btnPrev = $('#btnPrev').on('click', function () {
                        if ((index - 1) > -1) {
                            index--;
                            loadTrack(index);
                            if (playing) {
                                audio.play();
                            }
                        } else {
                            audio.pause();
                            index = 0;
                            loadTrack(index);
                        }
                    }),
                    btnNext = $('#btnNext').on('click', function () {
                        if ((index + 1) < trackCount) {
                            index++;
                            loadTrack(index);
                            if (playing) {
                                audio.play();
                            }
                        } else {
                            audio.pause();
                            index = 0;
                            loadTrack(index);
                        }
                    }),
                    li = $('#plList li').on('click', function () {
                        var id = parseInt($(this).index());
                        if (id !== index) {
                            playTrack(id);
                        }
                    }),
                    loadTrack = function (id) {
                        $('.plSel').removeClass('plSel');
                        $('#plList li:eq(' + id + ')').addClass('plSel');
                        npTitle.text(tracks[id].name);
                        index = id;
                        audio.src = mediaPath + tracks[id].file + extension;
                    },
                    playTrack = function (id) {
                        loadTrack(id);
                        audio.play();
                    };
                // extension = audio.canPlayType('audio/mpeg') ? '.mp3' : audio.canPlayType('audio/ogg') ? '.ogg' : '';
                loadTrack(index);
            } else {
                // no audio support
                $('.column').addClass('hidden');
                var noSupport = $('#audio1').text();
                $('.container').append('<p class="no-support">' + noSupport + '</p>');
            }
        });

    </script>
@endpush
