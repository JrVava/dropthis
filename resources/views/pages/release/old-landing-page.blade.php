@extends('layout.default')

@section('title', 'Landing Page')

@push('css')
    <link href="/assets/plugins/lity/dist/lity.min.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="/assets/plugins/lity/dist/lity.min.js"></script>
@endpush

@php
    $folderName = "music-label/";
    $musicLabels = [
        'amazonMusic'=>asset($folderName.'amazonMusic.png'),
        'amazonStore'=>asset($folderName.'amazonStore.png'),
        'appleMusic'=>asset($folderName.'appleMusic.png'),
        'beatport'=>asset($folderName.'beatport.png'),
        'concertTickets'=>asset($folderName.'concertTickets.png'),
        'deezer'=>asset($folderName.'deezer.png'),
        'itunes'=>asset($folderName.'itunes.svg'),
        'jpc'=>asset($folderName.'jpc.png'),
        'mediaMarkt'=>asset($folderName.'mediaMarkt.png'),
        'saturn'=>asset($folderName.'saturn.png'),
        'soundcloud'=>asset($folderName.'soundcloud.svg'),
        'spotify' => asset($folderName.'spotify.png'),
        'youtube'=>asset($folderName.'youtube.png'),
    ];
@endphp
@section('content')
    <div class="card p-4 w-25 container-sm bg-white opacity-92">
        <div class="card-body p-0">
            <!-- BEGIN profile -->
            <div class="profile">
                <!-- BEGIN profile-container -->
                <div class="profile-container">
                    <!-- BEGIN profile-sidebar -->
                    <div class="profile-sidebars">
                        <div class="desktop-sticky-top">
                            <div class="profile-img h-100">
                                <img src="{{ $release->cover }}" class="rounded" alt="" />
                            </div>
                            <!-- profile info -->
                            <h4>{{ $release->artist }}</h4>
                            <p>
                                {{ $release->track }}
                            </p>
                            <hr class="mt-4 mb-4" />
                            <!-- people-to-follow -->
                            {{-- <div class="fw-bold mb-3 fs-16px">People to follow</div> --}}
                            @foreach($musicLabels as $key => $musicLabel)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{$musicLabel}}" alt="" width="100" />
                                <div class="flex-fill px-3">
                                    {{-- <div class="fw-bold text-truncate w-100px">Noor Rowe</div>
                                    <div class="fs-12px text-white text-opacity-50">3.1m followers</div> --}}
                                </div>
                                <a href="#" class="btn btn-outline-theme">Play</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- END profile-sidebar -->
                </div>
                <!-- END profile-container -->
            </div>
            <!-- END profile -->
        </div>
        <div class="card-arrow">
            <div class="card-arrow-top-left"></div>
            <div class="card-arrow-top-right"></div>
            <div class="card-arrow-bottom-left"></div>
            <div class="card-arrow-bottom-right"></div>
        </div>
    </div>
@endsection
