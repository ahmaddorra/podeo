@extends('layouts.app')
@push('css')
    <style>

        #columns {
            list-style-type: none;
        }

        .column {
            display: -webkit-box;
            width: 162px;
            padding-bottom: 5px;
            padding-top: 5px;
            text-align: center;
        }

        .column header {
            height: 45px;
            width: 350px;
            color: black;
            background-color: #ccc;
            padding: 5px;
            border-radius: 10px;
            border: 2px solid #666666;
        }

        .column.over {
            border-top: 2px solid blue;
        }


    </style>
@endpush
@section('content')
    <div class="container-fluid p-4">
        <form action="{{ route('creator.podcasts.update', $podcast->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                       value="{{$podcast->name}}" aria-describedby="title">
                @error('title')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <div class="form-floating">
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              placeholder="Leave a description here" name="description" id="description"
                              style="height: 100px">{{$podcast->description}}</textarea>
                    <label for="description">Description</label>
                    @error('description')
                    <div class="invalid-feedback">
                        {{$message}}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-select @error('category') is-invalid @enderror">
                    @foreach($categories as $category)
                        <option value="{{$category->id}}"
                                @if($podcast->category_id == $category->id) selected @endif>{{$category->name}}</option>
                    @endforeach
                </select>
                @error('category')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input class="form-control @error('image') is-invalid @enderror" type="file" name="image" id="image">
                @error('image')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary float-end">Save Changes</button>
        </form>
        <br/>
        <hr>
        <h5>Episodes</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEpisodeModal">
            New Episode
        </button>
        <div class="row">
            <ul id="columns">
                @foreach($podcast->episodes as $episode)

                    <li id="episode-{{$episode->id}}" class="column" draggable="true">
                        <header id="episode-name-{{$episode->id}}">{{$episode->name}}</header>
                        <button data-id="{{$episode->id}}" data-name="{{$episode->name}}"
                                data-link="{{asset('/public/storage/'.$episode->audio)}}"
                                data-description="{{$episode->description}}" class="btn btn-primary btn-episode-edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button data-id="{{$episode->id}}" data-name="{{$episode->name}}"
                                class="btn btn-danger btn-episode-delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>


    <!-- New Episode -->
    <div class="modal fade" id="newEpisodeModal" tabindex="-1" aria-labelledby="New Episode" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newEpisodeModalLabel">New Episode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newEpisodeForm">
                        @csrf
                        <div class="mb-3">
                            <label for="episodeTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" name="name" id="episodeTitle">
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a description here" name="description"
                                          id="episodeDescription" required style="height: 100px"></textarea>
                                <label for="episodeDescription">Description</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="audio" class="form-label">Audio</label>
                            <input class="form-control" type="file" name="audio" id="audio" >
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="add-episode" type="button" class="btn btn-primary">Add Episode</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Episode -->
    <div class="modal fade" id="editEpisodeModal" tabindex="-1" aria-labelledby="Edit Episode" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditEpisodeModalLabel">Edit Episode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEpisodeForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editEpisodeTitle" class="form-label">Title</label>
                            <input type="text" name="name" class="form-control" id="editEpisodeTitle">
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <textarea name="description" class="form-control" placeholder="Leave a description here"
                                          id="editEpisodeDescription" style="height: 100px"></textarea>
                                <label for="editEpisodeDescription">Description</label>
                            </div>
                        </div>
                        <div class="mb-3" id="audio-container">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="updateEpisode" data-id="" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var audioPlayer = new Audio();

        $(document).on('click', '.btn-episode-delete', function () {
            Swal.fire({
                title: "delete " + $(this).data('name') + "?",
                text: 'Once deleted, you will not be able to recover again !',
                icon: "warning",
                cancelButtonColor: '#d33',
                showCancelButton: true
            }).then((willDelete) => {
                if (!willDelete.isConfirmed) {
                    return false;
                } else {
                    let url = "{{route('creator.episodes.destroy', ':id')}}"
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
                                console.log(row.data('id'))
                                document.getElementById("episode-" + row.data('id')).remove()
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

        $(document).on('click', '.btn-episode-edit', function () {
            let route = "{{route('creator.episodes.update', ':id')}}"
            $('#editEpisodeTitle').val($(this).data('name'));
            $('#editEpisodeDescription').val($(this).data('description'));
            $('#editEpisodeForm').attr('action', route.replace(':id', $(this).data('id')));
            $("#updateEpisode").attr('data-id', $(this).data('id'))

            audioPlayer.autoplay = false
            audioPlayer.src = $(this).data('link')
            audioPlayer.controls = true
            audioPlayer.loop = true
            audioPlayer.crossOrigin = "anonymous";
            document.getElementById('audio-container').appendChild(audioPlayer);

            $("#editEpisodeModal").modal('show');
        });

        $(document).on('click', '#updateEpisode', function () {
            let FormData = $('#editEpisodeForm').serialize();
            $.ajax({
                url: $('#editEpisodeForm').attr('action'),
                data: FormData,
                method: 'POST',
                success: function (data) {
                    if (data.success === true) {
                        console.log(data)
                        console.log($("#updateEpisode").data("id"))
                        $('#editEpisodeModal').modal('hide')
                        $("#episode-name-" + $("#updateEpisode").data("id")).html(data.name)
                        Swal.fire('Updated!', 'Successfully', 'success')
                    }
                },
                error: function () {
                    if('message' in data.responseJSON){
                        if('errors' in data.responseJSON){
                            Swal.fire('Update', Object.values(data.responseJSON.errors).join('<br/>'), 'error')
                        } else{
                            Swal.fire('Update', data.message, 'error')
                        }
                    } else{
                        Swal.fire('Update', 'Failed', 'error')
                    }
                }
            })
        });

        $('#add-episode').on('click', function () {
            let formData = new FormData();
            formData.append('name', $('#episodeTitle').val())
            formData.append('description', $('#episodeDescription').val())
            formData.append('audio', audio.files[0], 'audio.mp3');
            formData.append('_token', "{{csrf_token()}}");
            formData.append('podcast_id', "{{$podcast->id}}");
            $.ajax({
                url: "{{route('creator.episodes.store')}}",
                async: true,
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.success === true) {
                        let url = "{{URL::to('/')}}"
                        $('#newEpisodeModal').modal('hide')
                        $("#columns").append(`
                        <li id="episode-` + data.episode.id + `" class="column" draggable="true"><header id="episode-name-` + data.episode.id + `">` + data.episode.name + `</header>
                    <button data-id="` + data.episode.id + `" data-link="`+url+`/public/storage/` + data.episode.audio + `" data-name="` + data.episode.name + `" data-description="` + data.episode.description + `" class="btn btn-primary btn-episode-edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button data-id="` + data.episode.id + `" data-name="` + data.episode.name + `" class="btn btn-danger btn-episode-delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </li>
                        `)

                        Swal.fire('Added!', 'Successfully', 'success')
                        document.getElementById('newEpisodeForm').reset()

                    }
                },
                error: function (data) {
                    if('message' in data.responseJSON){
                        if('errors' in data.responseJSON){
                            Swal.fire('Addition', Object.values(data.responseJSON.errors).join('<br/>'), 'error')
                        } else{
                            Swal.fire('Addition', data.message, 'error')
                        }
                    } else{
                        Swal.fire('Addition', 'Failed', 'error')
                    }
                }
            })
        })


        var myModalEl = document.getElementById('editEpisodeModal')
        myModalEl.addEventListener('hidden.bs.modal', function (event) {
           $('#audio-container').html("")
        })
    </script>
@endpush
