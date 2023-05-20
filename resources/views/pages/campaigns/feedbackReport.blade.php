<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <style>
            body{
                font-family: Arial, Helvetica, sans-serif;
            }
            .heading-block{
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 5%;
                width: 100%;
            }
            .date-block{
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .details-box{
                margin-left: auto;
                margin-right: auto;
                align-content: center;
                width: 100%;
                max-width: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                
            }
            h4{
                text-align: center;
            }
            .details-block {
                border: 1px solid;
                padding: 2%;
                margin-top: 5%;
                margin-bottom: 5%;
                background-color: antiquewhite;
            }
            #feedback-table {
                margin-top: 5%;
                font-family: Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
                max-width: 80%;
                margin-left: auto;
                margin-right: auto;
            }

            #feedback-table td, #feedback-table th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #feedback-table tr:nth-child(even){background-color: #f2f2f2;}

            #feedback-table tr:hover {background-color: #ddd;}
            img{
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 100%;
                width: 325px;
                /* height: 280px; */
            }
            #feedback-table th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                /* background-color: #3e04aa02;
                color: white; */
            }
            .chart-block {
                padding-top: 50px;
                padding-bottom: 20px;
                padding-left: 30px;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                /* max-width: 50%; */
            }
            .d-flex.fw-bold.small.mb-3 {
                margin-left: 20%;
            }
            .col-xl-4.col-lg-4 {
                padding-top: 60px;
                padding-bottom: 60px;
            }
            span.flex-grow-1 {
                color: black;
            }
            footer {
                position: fixed; 
                bottom: -30px; 
                left: 0px; 
                right: 0px;
                height: 50px; 
                font-size: 16px !important;
                text-align: center;
                line-height: 35px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: Arial, Helvetica, sans-serif;
                /* background-color: #4e9cff; */
            }
            
                /* .brand-logo {
                    text-decoration: none !important;
                    color: #000
                }
                .brand-img {
                    background: url(http://127.0.0.1:8000/assets/css/images/logo.png);
                    filter: brightness(0.5);
                    background-size: 100%;
                    background-repeat: no-repeat;
                    padding: 5px;
                    list-style: none;
                }
                .brand-text {
                    padding-left: 10px;
                } */
                .chart-heading{
                    margin-top:50px; 
                }
                .footer-url {
                    float: left;
                    width: 100%;
                    position: absolute;
                    left: 0;
                    bottom: 30px:
                }
                .brand {
                    position: absolute;
                    left: 0;
                    bottom: 10px:
                }
                .chart-block {
                   clear: both;
                   
                }
               
                tbody tr td, thead tr th {
                    font-weight: 400;
                }
                .chart-floating {
                    width: 30.5%;
                    float: left;
                    text-align: center;
                    height: 400px;
                    margin: 0 auto;
                }
                .chart-floating img {
                    width: 200px;
                    margin-top: 40px;
                }
                .feedback-main-block{
                    border: 1px solid black;
                    margin-top: 10px;
                    margin-bottom: 10px;
                    padding:5px;
                }
                .feedback-comment{
                    background-color:#ffd9bf;
                    padding: 5px 
                }
                .feedback-label {
                    padding-right: 40px;
                }
                .feedback-display-flex{
                    display: flex;
                }
                .press-page-cover-image {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                img{
                    margin-left: auto;
                    margin-right: auto;
                }
                .page-break {
                    page-break-after: always;
                }
                .press-page-track-user{
                    background-color:#ffd9bf;
                    border: 1px solid black;
                    margin-top: 10px;
                    margin-bottom: 30px;
                    padding:5px;
                }
                .press-page-release-descrption-heading{
                    background-color:#ffd9bf;
                    border: 1px solid black;
                    margin-top: 30px;
                    margin-bottom: 10px;
                    padding:5px;
                }
                .release-description{
                    border: 1px solid black;
                    padding: 10px;
                }
                .campaign-statistics-table{
                    margin-bottom: 20px;
                    /* border: 1px solid black; */
                }
                .statistics-label{
                    background-color:#ffd9bf;
                }
                /* .over-rating-text{
                    border-bottom: 1px solid black;
                    border-right: 1px solid black;
                } */
                
                 tbody {
                    border: 1px solid #000;
                }
               
                tbody tr td {
                    padding: 6px;
                    
                }
                tbody tr:first-child td {
                    background: #ffd9bf;
                    text-align: center;
                    padding: 2px;
                    font-weight: bold;                    
                }
           
                .downloaded-but-not-feedback-name, .review-but-download-name{
                    border: 1px solid black;
                    padding: 5px;
                }
                .odd{
                    background-color:#ffd9bf;
                    margin-bottom: 10px;
                }
                .even{
                    margin-bottom: 10px;
                }
                .dj-feedback-text{
                    margin-left: 30px;
                }

                /*Best Mix PDF*/
                .mix-pdf-block {
                    width: 600px;
                    text-align: center;
                }
                .mix-pdf-block img {
                    width: 600px;
                    text-align: center;
                    margin: 0 auto;
                }

                .overall-rating-block-left {
                    float: left;
                    width: 50%;
                    text-align: center;

                }
                .overall-rating-block-right {
                   float: right;
                   width: 50%;
                   text-align: center;
                }
                .overall-rating-block-right .chart-heading, .overall-rating-block-left .chart-heading, .mix-pdf-block .chart-heading {
                    margin-bottom: 30px
                }

                .line-height{ line-height: 12px; }
                .review-but-not-downloaded-heading{
                    margin-bottom: 20px;
                }

        </style>
    </head>
    <body>
        <footer>
            <div class="footer-url">
                Report generated by {{ $campaign->label }}
                {{-- {{ $authDetails->name." ".url('') }}  --}}
            </div>
            <div class="brand">
                <span class="brand-img">
                    {{-- <span class="brand-img-text text-theme">D</span> --}}
                </span>
                <span class="brand-text"> DROP THIS
                </span>
            </div>
        </footer>
        <main>
        <div class="container mt-5">
            <div style="height:90%">
                <div class="heading-block" style="text-align: center">
                    <h1>FEEDBACK REPORT</h1>
                </div>
                <div class="date-block" style="text-align: center">
                    <h3>
                        Report Generated : 
                        @php
                            echo date("l, d M Y");
                        @endphp
                    </h3>
                </div>
                <div class="details-box">
                    <div class="details-block">
                        <h4>{{$campaign->release_number}}</h4>
                        {{-- <h4>{{ $campaign->userDetails->name }}</h4> --}}
                        @php
                            $tracks = [];
                        @endphp
                        @foreach ($campaign->getTrack as $key => $track)
                            @php
                                $tracks[] = str_replace("-","<br>", $track->track);
                            @endphp
                        @endforeach
                        <h4>
                            {!! implode(',<br>', $tracks ) !!}
                        </h4>
                            <h4>{{$campaign->label}}</h4>
                    </div>
                </div>
            </div>
            <div class="page-break"></div>
            <div class="press-page-main-block">
                <div class="press-page-heading-block" style="text-align: center;">
                    PRESS PAGE                        
                </div>
                <div class="press-page-track-user" style="text-align: center;">
                    @php
                        $trackPressPage = [];
                    @endphp
                    @foreach ($campaign->getTrack as $key => $track)
                        @php
                            $trackPressPage[] = $track->track;
                        @endphp
                    @endforeach
                    {{-- {{ dd($track) }} --}}
                    {!! implode(', ', $trackPressPage ) !!}
                </div>
                <div class="press-page-cover-image" style="text-align:center">
                    <img src="{{ storage_path('app/public/uploads/campaigns/'.$campaign->id."/".$campaign->cover_artwork) }}" alt="">
                    {{-- <img src="{{ getFileFromStorage($campaignPath.$campaign->id."/".$campaign->cover_artwork) }}" alt="" width="400" height="350"> --}}
                </div>
                <div class="press-page-release-descrption">
                    <div class="press-page-release-descrption-heading" style="text-align: center;">
                        Release Description
                    </div>
                    <div class="release-description">
                        {!! $campaign->description !!}
                    </div>
                </div>
            </div>
            <div class="page-break"></div>
            <div class="campaign-summary-main-block">
                <div class="campaign-summary-heading">
                    <h4>CAMPAIGN SUMMARY</h4>
                </div>
                <table class="campaign-statistics-table" width="100%"  cellpadding="0" cellspacing="0">
                    <tr class="statistics-label">
                        <td width="100%" colspan="3" align="center" style="border-bottom: 1px solid #000;">
                            <label >STATISTICS</label>
                        </td>
                    </tr>
                    <tr>
                        <td width="80%" colspan="2" class="over-rating-text"  style="border-bottom: 1px solid #000;">
                            Overall Rating
                        </td>
                        <td width="20%" align="center" style="border-bottom: 1px solid #000; border-left: 1px solid #000;">
                            {{ $feedbackAverage }}/10
                        </td> 
                    </tr>
                    <tr class="supporting-text">
                        <td width="80%">
                            Supporting DJs
                        </td>
                        <td width="10%" align="center" style="border-left: 1px solid #000;">
                            {{ $feedbackSupportingYes }}
                        </td>
                        <td width="10%" align="center" style="border-left: 1px solid #000;">
                            {{ $totalfeedbacks > 0 ? number_format($feedbackSupportingYes/$totalfeedbacks*100 ,2) : '0.00' }}%
                        </td>
                    </tr>
                </table>


                <table class="campaign-statistics-table" width="100%"  cellpadding="0" cellspacing="0">
                    <tr class="statistics-label">
                        <td width="100%" colspan="2" align="center" style="border-bottom: 1px solid #000;">
                            <label >BEST MIX / TRACK</label>
                        </td>
                    </tr>                   

                    @foreach($bestmix as $bestmixCount)
                        <tr border="0">
                            <td width="80%" class="over-rating-text"  style="border-bottom: 1px solid #000;">
                                {{ $bestmixCount->bestMix }}
                            </td>
                            <td width="20%" align="center" style="border-bottom: 1px solid #000; border-left: 1px solid #000;">
                                {{ $totalfeedbacks > 0 ? number_format($bestmixCount->total/$totalfeedbacks*100 ,2) : '0.00' }}%
                            </td>
                        </tr>
                    @endforeach

                </table>

            </div>
            @if($platformEmpty != 'empty' || $browserEmpty != "empty" || $deviceEmpty != "empty")
            <div class="page-break"></div>
            @endif
            <div class="chart-block">
                {{-- top devices pie chart --}}
                @if($deviceEmpty != 'empty')
                    <div class="top-device-block chart-floating">
                        <div class="chart-heading">
                            <span class="flex-grow-1">TOP DEVICES</span>
                        </div>
                        <img class="device-canvas" id="device-canvas" src="{{ $device }}" />
                    </div>
                @endif
                {{-- top browsers pie chart --}}
                @if($browserEmpty != 'empty')
                    <div class="top-browsers-block chart-floating">
                        <div class="chart-heading">
                            <span class="flex-grow-1">TOP BROWSERS</span>
                        </div>
                        <img src="{{ $browser }}" alt="">
                    </div>
                @endif
                {{-- top platforms pie chart --}}
                @if($platformEmpty != 'empty')
                    <div class="top-platforms-block chart-floating">
                        <div class="chart-heading">
                            <span class="flex-grow-1">TOP PLATFORMS</span>
                        </div>
                        <img src="{{ $platform }}" alt="">
                    </div>
                @endif
        </div>
        @if($bestMixChartEmpty != 'empty' || $ratingChartEmpty != "empty" || $beakdownChartEmpty != "empty")
            <div class="page-break"></div>
        @endif
        <div class="chart-block">
                @if($bestMixChartEmpty != 'empty')
                    <div class="mix-pdf-block">
                        <div class="chart-heading">
                            <span class="flex-grow-1">BEST MIX</span>
                        </div>
                        <img src="{{ $bestMixChart }}" alt="">
                    </div>
                @endif
                @if($ratingChartEmpty != "empty")
                    <div class="overall-rating-block-left">
                        <div class="chart-heading">
                            <span class="flex-grow-1">OVERALL RATING</span>
                        </div>
                        <img src="{{ $ratingChart }}" alt="">
                    </div>
                @endif
                @if($beakdownChartEmpty != "empty")
                    <div class="overall-rating-block-right">
                        <div class="chart-heading">
                            <span class="flex-grow-1">RATING BREAKDOWN</span>
                        </div>
                        <img src="{{ $beakdownChart }}" alt="">
                    </div>
                @endif
            </div>
        </div>
        <?php if(!empty($feedbacks->toArray())) { ?>
            <div class="page-break"></div>
            <div class="dj-feedback-heading" style="text-align: center;">
                DJ FEEDBACK
            </div>
            @php $checkFeedbacks=0 @endphp
            @foreach($feedbacks as $key => $feedback)
                @php $checkFeedbacks++ @endphp
                <div class="feedback-main-block">
                    <div class="feedback-owner" style="font-size: 16px; font-weight: bold;">
                        {{ $feedback->name }}
                    </div>
                    <div class="feedback-rating feedback-display-flex" style="width:100%; display:flex;">
                        Rating <label style="margin-left: 30px;">:</label> <label class="dj-feedback-text">{{ $feedback->rating }}/10</label>
                    </div>
                    <div class="feedback-comment" style="font-size: 12px">{{ $feedback->dj_quote }}</div>
                    <div class="feedbacl-bestmix feedback-display-flex">
                        Best Mix <label style="margin-left: 14px;">:</label> <label class="dj-feedback-text">{{ $feedback->best_mix }}</label>
                    </div>
                    <div class="feedback-support feedback-display-flex">
                        Support <label style="margin-left: 22px;">:</label> <label class="dj-feedback-text">{{ $feedback->supporting == 1 ? 'YES' : 'NO' }}</label>
                    </div>
                </div>
                @if(  $checkFeedbacks % 7 == 0 )
                    <div class="page-break"></div>
                @endif
            @endforeach
        <?php } ?>
        
        @if(!empty($reviewButNotDownloaded->toArray()))
        <div class="page-break"></div>
        <div class="review-but-download-main-block">
            <div class="review-but-not-downloaded-heading" style="text-align: center">
                VIEWED BUT NOT DOWNLOADED
            </div>
            @php $checkReviewButNotDownloaded=0 @endphp
            @foreach($reviewButNotDownloaded as $num => $notDownloaded)
            @if(!empty($notDownloaded->user_id) || !empty($notDownloaded->email))
            @php $checkReviewButNotDownloaded++ @endphp
                <div class="review-but-download-name @if($num % 2 == 0) even @else odd @endif">
                    @if(!empty($notDownloaded->user_id))
                        {{ $notDownloaded->getUser() }}
                    @elseif(!empty($notDownloaded->email))
                        {{ $notDownloaded->getEmailGroup() }}
                    @endif
                </div>
                @if( $checkReviewButNotDownloaded % 22 == 0 )
                    <div class="page-break"></div>
                @endif
            @endif
            @endforeach
        </div>
        @endif        
        @if(!empty($downloadedButNotLeftFeedbacks->toArray()))
        <div class="page-break"></div>
        <div class="download-but-not-left-feedback->main-block">
            <div class="download-but-not-left-feedback-heading" style="text-align: center">
                DOWNLOADED BUT NOT LEFT FEEDBACK
            </div>    
            @php $checkDownloadedButNotLeftFeedbacks=0 @endphp   
            @foreach($downloadedButNotLeftFeedbacks as $key => $downloadedButNotLeftFeedback)
                @if(!empty($downloadedButNotLeftFeedback->user_id) || !empty($downloadedButNotLeftFeedback->email))
                @php $checkDownloadedButNotLeftFeedbacks++ @endphp
                    <div class="downloaded-but-not-feedback-name @if($num % 2 == 0) even @else odd @endif">
                        @if(!empty($downloadedButNotLeftFeedback->user_id))
                            {{ $downloadedButNotLeftFeedback->getUser() }}
                        @elseif(!empty($downloadedButNotLeftFeedback->email))
                            {{ $downloadedButNotLeftFeedback->getEmailGroup() }}
                        @endif
                    </div>
                    @if( $checkDownloadedButNotLeftFeedbacks % 22 == 0 )
                        <div class="page-break"></div>
                    @endif
                @endif
            @endforeach
        </div>
        @endif
        </main>
        <script type="text/php">
            if (isset($pdf)) {
                $text = 'Page {PAGE_NUM} / {PAGE_COUNT}';
                $size = 12;
                $font = $fontMetrics->getFont("Arial");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 1;
                $y = $pdf->get_height() - 40;
                $pdf->page_text($x, $y,$text, $font, $size);
            }

        </script>
    </body>
</html>