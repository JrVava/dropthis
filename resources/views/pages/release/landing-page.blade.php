@php
   //  $folderName = "music-label/";
   //  $musicLabels = [
   //      'spotify' => asset($folderName.'spotify.png'),
   //      'deezer'=>asset($folderName.'deezer.png'),
   //      'tidal'=>asset($folderName.'tidal.png'),
   //      'youtube'=>asset($folderName.'youtube.png'),
   //      'amazonMusic'=>asset($folderName.'amazonMusic.png'),
   //      'amazonStore'=>asset($folderName.'amazonStore.png'),
   //      'appleMusic'=>asset($folderName.'appleMusic.png'),
   //      'beatport'=>asset($folderName.'beatport.png'),
   //      'concertTickets'=>asset($folderName.'concertTickets.png'),
   //      'itunes'=>asset($folderName.'itunes.png'),
   //      'jpc'=>asset($folderName.'jpc.png'),
   //      'mediaMarkt'=>asset($folderName.'mediaMarkt.png'),
   //      'saturn'=>asset($folderName.'saturn.png'),
   //      'soundcloud'=>asset($folderName.'soundcloud.svg'),
   //  ];
   //  ksort($musicLabels);
   //  echo "<pre>";
   //  foreach($release->platform as $platform){
   //    foreach($platform->getStore as $store){
   //       echo $store->base_url."<br>";
   //    }
   //  }
   //  exit;
@endphp
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="description" content="">
      <meta name="theme-color" content="#EF5366">
      <meta name="author" content="CLOUDVEIL">
      <link rel="icon" href="{{ asset('landingPage/images/favicon.ico') }}">
      <title></title>
      <!-- Bootstrap core CSS -->
      <!-- Custom styles for this template -->
      <link href="{{ asset('landingPage/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('landingPage/css/custom.css') }}" rel="stylesheet">
      <link href="/assets/css/vendor.min.css" rel="stylesheet" />
   </head>
   <body>
      
      <div class="main-wrapper active">
         <img class="main-wrapper-img" src="{{ $release->cover }}">
         <main class="inner-block">
            <div class="inner-content">
               <div class="top-card-block" style="background-image: url({{ $release->cover }});">
                  <div class="card-inner">
                     {{-- Visulizer Start Here --}}                              
                     <div id="eqWave" class="d-none"></div>
                    {{-- Visulizer End Here --}}
                     <div class="card-img-block ">    
                        <div class="moving-audio-text"></div>   
                        <div class="audio-timer d-none">00:00 </div>
                        <div class="img-block-action">
                           <img src="{{ $release->cover }}" alt="Artwork">
                        </div>
                        <div class="audio-preview">
                           <div class="control-bar">
                              <img src="{{ asset('player-icon/play_button.svg') }}" alt="play" id="playBtn" title="Play" class="playButton">
                              <img src="{{ asset('player-icon/pause_button.svg') }}" alt="play" id="pause" title="Pause" class="pauseButton d-none">
                           </div>
                           <div id="initiallyInvisible">
                              <audio controls id="audio"></audio>                              
                           </div>
                        </div>
                     </div>
                     <div class="text-center mt-5">
                        <h2>{{ $release->artist }}</h2>
                        <p>{{ $release->track }}</p>
                     </div>
                  </div>
               </div>
               <div class="logo-wrapper">
                  <div class="logo-section">
                     <div class="bottom-arrow-block">
                        <a href="#middle" class="scrollTo">
                           <div class="arrow-img" role="button" tabindex="0">
                              <svg xmlns="http://www.w3.org/2000/svg" width="12.779" height="10.853" viewBox="0 0 12.779 10.853">
                                 <path d="M0,9.578,4.142,5.427,0,1.275,1.275,0,6.7,5.427,1.275,10.853Z" fill="#7a7a9a"></path>
                                 <path d="M0,9.578,4.142,5.427,0,1.275,1.275,0,6.7,5.427,1.275,10.853Z" transform="translate(6.077)" fill="#7a7a9a"></path>
                              </svg>
                           </div>
                        </a>
                     </div>
                        @foreach($release->platform as $key => $platform)
                        
                           {{-- @php
                              $musicPlatFormUrl = [];
                              foreach($files as $file){            
                                 if (str_contains($file, $musicLabel->code)) {  
                                       $musicPlatFormUrl[] =  $file;
                                    }
                              }
                              $musicPlatFormImage = implode(" ",$musicPlatFormUrl);
                              $imageUrl = asset('music-label/'.basename($musicPlatFormImage));
                           @endphp --}}
                           <div class="logo-product"  id="middle">
                              <div class="logo-block">
                                 <div class="logo-items">
                                    @foreach($platform->getStore as $store){
                                    <div class="logo-item-left">
                                          <img src="{{$store->dark_logo}}" alt="spotify">                                       
                                    </div>
                                    <div class="logo-item-right">
                                       @php
                                          $url = '';
                                          if(!empty($platform->url)){
                                             $url = $platform->url;
                                          }elseif(!empty($platform->track_id)){
                                             $url = $store->base_url.'/'.$platform->track_id;
                                          }
                                       @endphp
                                       <a href="{{ route('landing-page',['id'=>$release->id,'platform_id'=>$platform->id,'musicPlatForm'=>$store->id,'slug'=>$release->slug]) }}" class="btn play-btn">Play<span></span></a>
                                       {{-- <a href="{{ $url }}" class="btn play-btn">Play<span></span></a> --}}
                                    </div>
                                    @endforeach
                                 </div>
                              </div>
                           </div>
                        @endforeach
                  </div>
                  <div class="footer-block">
                     <div class="mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="6" viewBox="0 0 18 6">
                           <g id="Groupe_1151" data-name="Groupe 1151" transform="translate(-179 -887)">
                              <circle id="Ellipse_112" data-name="Ellipse 112" cx="1" cy="1" r="1" transform="translate(179 887)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_127" data-name="Ellipse 127" cx="1" cy="1" r="1" transform="translate(195 887)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_119" data-name="Ellipse 119" cx="1" cy="1" r="1" transform="translate(179 891)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_123" data-name="Ellipse 123" cx="1" cy="1" r="1" transform="translate(195 891)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_113" data-name="Ellipse 113" cx="1" cy="1" r="1" transform="translate(183 887)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_118" data-name="Ellipse 118" cx="1" cy="1" r="1" transform="translate(183 891)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_114" data-name="Ellipse 114" cx="1" cy="1" r="1" transform="translate(187 887)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_117" data-name="Ellipse 117" cx="1" cy="1" r="1" transform="translate(187 891)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_115" data-name="Ellipse 115" cx="1" cy="1" r="1" transform="translate(191 887)" fill="#c9c9de"></circle>
                              <circle id="Ellipse_116" data-name="Ellipse 116" cx="1" cy="1" r="1" transform="translate(191 891)" fill="#c9c9de"></circle>
                           </g>
                        </svg>
                     </div>

                     <div class="form-group row footer-social">                     
                        @if($release->facebook_url != null)                           
                           <a href="{{ $release->facebook_url }}">
                              <i class="bi bi-facebook fa-3x" style="color:white;"></i>
                           </a>                           
                        @endif
                        @if($release->twitter_url != null)                           
                           <a href="{{ $release->twitter_url }}">
                              <i class="bi bi-twitter fa-3x" style="color:white;"></i>
                           </a>                     
                        @endif
                        @if($release->youtube_url != null)                           
                           <a href="{{ $release->youtube_url }}">
                              <i class="bi bi-youtube fa-3x" style="color:white;"></i>
                           </a>                           
                        @endif
                        @if($release->spotify_url != null)                           
                           <a href="{{ $release->spotify_url }}">
                              <i class="bi bi-spotify fa-3x" style="color:white;"></i>
                           </a>                           
                        @endif
                        @if($release->instagram_url != null)                           
                           <a href="{{ $release->instagram_url }}">
                              <i class="bi bi-instagram fa-3x" style="color:white;"></i>
                           </a>                           
                        @endif
                        @if($release->soundcloud_url != null)                           
                           <a href="{{ $release->soundcloud_url }}">
                              <i class="fab fa-soundcloud fa-3x" style="color:white;"></i>
                           </a>                           
                        @endif
                        @if($release->tiktok_url != null)                           
                           <a href="{{ $release->tiktok_url }}">
                              <i class="bi bi-tiktok fa-3x" style="color:white;"></i>
                           </a>                           
                        @endif
                        @if($release->web_url != null)                           
                           <a href="{{ $release->web_url }}">
                              <i class="bi bi-globe fa-3x" style="color:white;"></i>
                           </a>                           
                        @endif
                     </div>
                     <ul class="p-0">
                        <li>
                           <a href="https://www.iubenda.com/privacy-policy/96420840/legal" target="_blank">Privacy</a>
                        </li>
                     </ul>
                     <div class="mt-5">
                        <a href="" class="poweredby">
                           {{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="107" height="44.371" viewBox="0 0 107 44.371">
                              <defs>
                                 <clipPath id="clipPath">
                                    <path id="Tracé_1823" data-name="Tracé 1823" d="M.5-16H5.056v6.243a5.879,5.879,0,0,1,4.413-1.726A7.5,7.5,0,0,1,14.948-9.1a7.5,7.5,0,0,1,2,5.634c0,5.49-3.8,8.229-7.671,8.229A4.906,4.906,0,0,1,4.913,2.625h0V4.247H.5ZM8.586.522a3.8,3.8,0,0,0,2.73-1.141A3.8,3.8,0,0,0,12.4-3.371,3.764,3.764,0,0,0,10.59-6.815a3.764,3.764,0,0,0-3.891,0A3.764,3.764,0,0,0,4.887-3.371,3.712,3.712,0,0,0,5.9-.633,3.712,3.712,0,0,0,8.586.522Z" transform="translate(-0.5 16)" fill="none"></path>
                                 </clipPath>
                                 <clipPath id="clipPath-2">
                                    <path id="Tracé_1825" data-name="Tracé 1825" d="M30.044-1.572a9.007,9.007,0,0,1-3.1,3.894,7.359,7.359,0,0,1-4.335,1.4,8.112,8.112,0,0,1-7.622-4.945,8.112,8.112,0,0,1,1.747-8.916,8.112,8.112,0,0,1,8.924-1.7,8.112,8.112,0,0,1,4.907,7.646A6.217,6.217,0,0,1,30.446-3h-11.4A3.4,3.4,0,0,0,22.607-.17,3.193,3.193,0,0,0,25.372-1.6ZM26.008-6.089a3.349,3.349,0,0,0-3.452-2.6,3.349,3.349,0,0,0-3.452,2.6Z" transform="translate(-14.342 12.499)" fill="none"></path>
                                 </clipPath>
                                 <clipPath id="clipPath-3">
                                    <path id="Tracé_1827" data-name="Tracé 1827" d="M28.78,4.247h4.543V-16H28.78Z" transform="translate(-28.78 16)" fill="none"></path>
                                 </clipPath>
                                 <clipPath id="clipPath-4">
                                    <path id="Tracé_1829" data-name="Tracé 1829" d="M34.82,3.091h4.543V-12.12H34.82Z" transform="translate(-34.82 12.12)" fill="none"></path>
                                 </clipPath>
                                 <clipPath id="clipPath-5">
                                    <path id="Tracé_1831" data-name="Tracé 1831" d="M55.764-1.572a8.917,8.917,0,0,1-3.1,3.894,7.359,7.359,0,0,1-4.335,1.4,8.112,8.112,0,0,1-7.622-4.945,8.112,8.112,0,0,1,1.747-8.916,8.112,8.112,0,0,1,8.924-1.7,8.112,8.112,0,0,1,4.907,7.646A6.412,6.412,0,0,1,56.179-3H44.771A3.4,3.4,0,0,0,48.327-.17,3.232,3.232,0,0,0,51.1-1.6ZM51.728-6.089a3.6,3.6,0,0,0-3.452-2.592,3.6,3.6,0,0,0-3.452,2.592Z" transform="translate(-40.062 12.499)" fill="none"></path>
                                 </clipPath>
                                 <clipPath id="clipPath-6">
                                    <path id="Tracé_1833" data-name="Tracé 1833" d="M62.982,3.091H58.621L53.17-12.12h4.711l2.907,9.475h.065l2.894-9.475h4.724Z" transform="translate(-53.17 12.12)" fill="none"></path>
                                 </clipPath>
                                 <clipPath id="clipPath-7">
                                    <path id="Tracé_1835" data-name="Tracé 1835" d="M81.116-1.572a8.826,8.826,0,0,1-3.1,3.894,7.346,7.346,0,0,1-4.374,1.4,8.112,8.112,0,0,1-7.631-4.935,8.112,8.112,0,0,1,1.738-8.92,8.112,8.112,0,0,1,8.926-1.709,8.112,8.112,0,0,1,4.91,7.647A6.413,6.413,0,0,1,81.479-3H70.071A3.4,3.4,0,0,0,73.64-.17,3.232,3.232,0,0,0,76.4-1.6ZM77.092-6.089A3.6,3.6,0,0,0,73.634-8.7a3.6,3.6,0,0,0-3.459,2.615Z" transform="translate(-65.362 12.499)" fill="none"></path>
                                 </clipPath>
                                 <clipPath id="clipPath-8">
                                    <path id="Tracé_1837" data-name="Tracé 1837" d="M82.282-1.053A1.921,1.921,0,0,1,80.335.841,1.921,1.921,0,0,1,78.44-1.105,1.921,1.921,0,0,1,80.387-3a1.908,1.908,0,0,1,1.355.577,1.908,1.908,0,0,1,.54,1.37Zm-3.413,0A1.532,1.532,0,0,0,80.374.491a1.544,1.544,0,0,0,1.454-.926,1.544,1.544,0,0,0-.309-1.7,1.545,1.545,0,0,0-1.688-.353A1.544,1.544,0,0,0,78.868-1.053ZM80.452-2.13c.415,0,.779.169.779.6a.532.532,0,0,1-.337.519c.156,0,.286.169.3.493a1.857,1.857,0,0,0,0,.519h-.519a3.606,3.606,0,0,1,0-.506c0-.13,0-.311-.376-.311h-.208V0h-.532V-2.13Zm-.363.363v.6h.247c.117,0,.363,0,.363-.324s-.221-.273-.324-.273Z" transform="translate(-78.44 3)" fill="none"></path>
                                 </clipPath>
                                 <clipPath id="clipPath-9">
                                    <path id="Tracé_1839" data-name="Tracé 1839" d="M37.157-12.886a2.557,2.557,0,0,0,2.557-2.557A2.557,2.557,0,0,0,37.157-18,2.557,2.557,0,0,0,34.6-15.443,2.557,2.557,0,0,0,37.157-12.886Z" transform="translate(-34.6 18)" fill="none"></path>
                                 </clipPath>
                              </defs>
                              
                              <g id="Groupe_1453" data-name="Groupe 1453" transform="translate(-135 -1136)">
                                 <text id="powered_by" data-name="POWERED BY" transform="translate(188 1147)" fill="#5d5d75" font-size="10" font-family="OpenSans-Regular, Open Sans" letter-spacing="0.158em">
                                    <tspan x="-38.182" y="0">POWERED BY</tspan>
                                 </text>
                                 <g id="Groupe_1643" data-name="Groupe 1643" transform="translate(136.5 1175)">
                                    <g id="Groupe_1634" data-name="Groupe 1634" transform="translate(0.5 -15.404)" clip-path="url(#clipPath)">
                                       <path id="Tracé_1822" data-name="Tracé 1822" d="M-4.5-21H24.941V12.755H-4.5Z" transform="translate(-1.989 14.511)" fill="#ffffff"></path>
                                    </g>
                                    <g id="Groupe_1635" data-name="Groupe 1635" transform="translate(18.465 -10.86)" clip-path="url(#clipPath-2)">
                                       <path id="Tracé_1824" data-name="Tracé 1824" d="M9.342-17.5h29.2V11.7H9.342Z" transform="translate(-15.831 11.01)" fill="#ffffff"></path>
                                    </g>
                                    <g id="Groupe_1636" data-name="Groupe 1636" transform="translate(37.204 -15.404)" clip-path="url(#clipPath-3)">
                                       <path id="Tracé_1826" data-name="Tracé 1826" d="M23.78-21H41.3V12.226H23.78Z" transform="translate(-30.269 14.511)" fill="#ffffff"></path>
                                    </g>
                                    <g id="Groupe_1637" data-name="Groupe 1637" transform="translate(45.044 -10.368)" clip-path="url(#clipPath-4)">
                                       <path id="Tracé_1828" data-name="Tracé 1828" d="M29.82-17.12H47.342V11.07H29.82Z" transform="translate(-36.309 10.631)" fill="#ffffff"></path>
                                    </g>
                                    <g id="Groupe_1638" data-name="Groupe 1638" transform="translate(51.847 -10.86)" clip-path="url(#clipPath-5)">
                                       <path id="Tracé_1830" data-name="Tracé 1830" d="M35.062-17.5h29.2V11.7h-29.2Z" transform="translate(-41.551 11.01)" fill="#ffffff"></path>
                                    </g>
                                    <g id="Groupe_1639" data-name="Groupe 1639" transform="translate(68.86 -10.368)" clip-path="url(#clipPath-6)">
                                       <path id="Tracé_1832" data-name="Tracé 1832" d="M48.17-17.12H76.451V11.07H48.17Z" transform="translate(-54.659 10.631)" fill="#ffffff"></path>
                                    </g>
                                    <g id="Groupe_1640" data-name="Groupe 1640" transform="translate(84.683 -10.86)" clip-path="url(#clipPath-7)">
                                       <path id="Tracé_1834" data-name="Tracé 1834" d="M60.362-17.5h29.2V11.7h-29.2Z" transform="translate(-66.851 11.009)" fill="#ffffff"></path>
                                    </g>
                                    <g id="Groupe_1641" data-name="Groupe 1641" transform="translate(101.658 1.468)" clip-path="url(#clipPath-8)">
                                       <path id="Tracé_1836" data-name="Tracé 1836" d="M73.44-8H90.261V8.821H73.44Z" transform="translate(-79.93 1.511)" fill="#ffffff"></path>
                                    </g>
                                    <g id="Groupe_1642" data-name="Groupe 1642" transform="translate(44.758 -18)" clip-path="url(#clipPath-9)">
                                       <path id="Tracé_1838" data-name="Tracé 1838" d="M29.6-23H47.693V-4.907H29.6Z" transform="translate(-36.089 16.511)" fill="#ffffff"></path>
                                    </g>
                                 </g>
                              </g>
                           </svg> --}}
                              <span>POWERED BY</span>
                              <img src="{{asset('landingPage/images/landing_page_logo.png')}}" />
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </main>
      </div>
      <!-- Bootstrap core JavaScript
         ================================================== -->
      <!-- Placed at the end of the document so the pages load faster -->
      {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
      <script src="/assets/js/vendor.min.js"></script>
<script src="/assets/js/app.min.js"></script>
      <script src="{{ asset('landingPage/js/bootstrap.min.js') }}"></script>
      
      <script>
         $(document).ready(function(){
            var audioElement = document.createElement('audio');
            audioElement.setAttribute('src', "{{$release->audio_preview}}");
            
            audioElement.addEventListener('ended', function() {
              $('#playBtn').removeClass('d-none');
               $('#pause').addClass('d-none');
            }, false);
            audioElement.addEventListener("timeupdate",function(){
               $('.audio-timer').removeClass('d-none');
               $('.audio-timer').text(secondsToTimestamp(audioElement.currentTime));
            });
            
               var html = '';
               const bar = 44;
               for(let i=1; i < bar; i++){                 
                  html += '<div class="eqBar" id="Bar'+i+'"> </div>';
               }
               $('#eqWave').html(html);
               let c = 1;
               for(let i=1; i < bar; i++){            
                  $('#Bar'+i).css('animationDuration','400ms');
                  if(c === 7) c = 0 ;
                  $('#Bar'+i).css('animationDelay',(c == 0 ? c : c*133)+'ms');
                  c++;
               }

            $('#playBtn').click(function(){
               audioElement.play();
               $('.audio-timer').show();
               $('#eqWave').removeClass('d-none');               
               $('.moving-audio-text').html('<marquee direction="right" scrollamount="5">{{ $release->artist }} {{ $release->track }}</marquee>');
               $('#pause').removeClass('d-none');
               $(this).addClass('d-none');
            });
            $('#pause').click(function(){
               $('.audio-timer').css('display','none');
               audioElement.pause();
               $('.moving-audio-text').html('');
               $('#eqWave').addClass('d-none');
               $('#playBtn').removeClass('d-none');
               $(this).addClass('d-none');
            });
         });
         function secondsToTimestamp(seconds) {
            seconds = Math.floor(seconds);
            var h = Math.floor(seconds / 3600);
            var m = Math.floor((seconds - (h * 3600)) / 60);
            var s = seconds - (h * 3600) - (m * 60);
            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            return m + ':' + s;
         }
      </script>
   </body>
</html>