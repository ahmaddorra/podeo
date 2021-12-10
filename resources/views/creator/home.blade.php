@extends('layouts.app')
@push('css')
    <link href="{{ asset('public/css/creator/podcast.css') }}" rel="stylesheet">
@endpush
@section('content')
    <section class="container" id="podcast-container">
        <!-- Header Bar -->
        <header>
            <a href="{{route('creator.podcasts.create')}}" class="button-col">
                <button class="btn btn-info" name="Add Task"> Add Podcast</button>
            </a>
            <div class="dates-col">
                <label> Category </label>
            </div>

            <div class="dates-col">
                <label> Uploaded </label>
            </div>

            <div class="dates-col">
                <label> Actions </label>
            </div>
        </header>

        <!-- List Items -->
        <ul class="task-items">

        @foreach($podcasts as $podcast)
            <!-- List Item -->

                <li class="item type2" id="podcast-{{$podcast->id}}">
                    <div class="task">
                        <div class="icon"><img src="{{asset('public/storage/'.$podcast->image)}}" alt="" width="50" height="50"/></div>
                        <div class="name">{{$podcast->name}} </div>
                    </div>
                    <div class="dates">
                        <div> {{$podcast->category->name}}</div>
                    </div>
                    <div class="dates">
                        <div>{{$podcast->created_at->format('Y-m-d')}}</div>
                    </div>

                    <div class="dates">
                        <a href="{{route('creator.podcasts.edit', $podcast->id)}}" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-delete" data-id="{{$podcast->id}}"
                                data-podcast-name="{{$podcast->name}}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </li>
            @endforeach


        </ul>
    </section>
@endsection
@push('js')
    <script>
        $('.btn-delete').on('click', function () {
            Swal.fire({
                title: "delete " + $(this).data('podcast-name') + "?",
                text: 'Once deleted, you will not be able to recover again !',
                icon: "warning",
                cancelButtonColor: '#d33',
                showCancelButton: true
            }).then((willDelete) => {
                if (!willDelete.isConfirmed) {
                    return false;
                } else {
                    let url = "{{route('creator.podcasts.destroy', ':id')}}"
                    url = url.replace(':id', $(this).data('id'))
                    let row = $(this)
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function (data) {
                            if (data.success === true) {
                                console.log( row.data('id'))
                                document.getElementById("podcast-" + row.data('id')).remove()
                                Swal.fire('Deleted!', 'Successfully', 'success')
                            }
                        },
                        error: function () {
                            if('message' in data.responseJSON){
                                if('errors' in data.responseJSON){
                                    Swal.fire('Deletion', Object.values(data.responseJSON.errors).join('<br/>'), 'error')
                                } else{
                                    Swal.fire('Deletion', data.message, 'error')
                                }
                            } else{
                                Swal.fire('Deletion', 'Failed', 'error')
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
