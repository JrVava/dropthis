<html>
<body>
    <div style=" max-width: 600px; margin: 0 auto;background-color: #ffffff; height: 100%;
    color: #718096; ">
    {{-- {{ dd($track) }} --}}
    <div style=" padding: 32px 32px 0;">
    <div style="text-align: center">
        <p style="margin-bottom: 20px font-size: 12px; text-align: center">{{ $designArray1['campaign']->getTrack->first()->track }}</p>
        <img src="{{ $designArray1['path'] }}" width="200" style="height: 200px; object-fit:contain; margin: 0 auto;">
    </div>

    <div style="display: flex; justify-content: center">
    <table width="300px" style="margin: 20px auto 30px; font-size: 12px;">
    <tr>
    <td width="50%">
    Release Date              
    </td>
    <td width="50%" align="right">
    {{$designArray1['release_date']}}
    </td>
    </tr>
    <tr>
    <td>
    Label            
    </td>
    <td align="right">
    {{$designArray1['campaign']->label}}
    </td>
    </tr>
    <tr>
    <td>
    Catalog Number            
    </td>
    <td align="right">
    {{$designArray1['campaign']->release_number}}
    </td>
    </tr>
    </table>
    </div>
    <a href="javascript:void(0)" style="width: 100%; font-size: 12px; text-align: center; background: #2d6ac5; padding: 12px 25px; display: block; margin-top:20px" class="button">Click here to listen and download</a>
    <br>

    <p style="margin: 30px 0 font-size:12px; text-align: left;">{!! str_replace('white-space: pre;',"",$designArray1['description']) !!}</p>

    @if(isset($designArray1['userImage']))
    <div style="text-align: center; margin: 35px 0">
    <img src="{{ $designArray1['userImage'] }}" alt="" width="50" height="50" style="margin: 0 auto --bs-bg-opacity: 0.25;">
    </div>
    @endif
</div>

    {{-- Thanks,<br>
    {{ config('app.name') }} --}}
    <div style="font-size:12px; padding-top: 20px; background: #fafafa; width: 100%; padding: 25px;">
    <div style=" margin-bottom:40px; text-align: center">
    <div style="margin-bottom:15px; width: 100%; display: block; align-items: center;">Delivered by PromoDrop for {{$designArray1['campaign']->label}}</div>
    <div style="display: block; width: 100%; align-items: center;">You can email {{$designArray1['campaign']->label}} at <a href="javascript:void(0)">{{ $designArray1['user']->email }}</a></div>
    </div>
    <div style="display: flex; width: 100%; justify-content: space-between">
    <div style="width: 50%">
    Question?<br> Need help?<br> Contact us at help@promodrop.me
    </div>
    <div style="width: 50%">
    Our mailing address is:<br>
    The Mount<br>
    2 Woodstock Link<br>
    Belfast, BT6 8DD<br>
    <a href="javascript:void(0)">Unsubscribe</a>
    </div>
    </div>
    </div>
    {{-- Bottom paragraph Start Here --}}
    <br>
    <div style="display: flex; justify-content: space-between; width: 100%; padding: 20px;">
        <div class="bottom-logo-block" style="width: 20%; font-size:16px; font-weight: bold;">
            PromoDrop
        </div>
        <div class="bottom-paragraph-block" style="width: 80%;">
            <p style="font-size:12px; ">
                Do you need a promotion tool like this? Visit <a style="text-decoration: none; color: #e01f4d" href="javascript:void(0)">promodrop.me</a> for more information.
                This promo was delivered by PromoDrop Limited for Label {{$designArray1['campaign']->label}}
            </p>
        </div>
    </div>
    {{-- Bottom paragraph End Here --}}
    </div>
    
</body>
</html>
