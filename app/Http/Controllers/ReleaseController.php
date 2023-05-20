<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Release;
use App\Models\ReleaseClick;
use DataTables;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;
use File;
use App\Models\Country;
use App\Models\ReleasePlatform;
use App\Models\Store;

class ReleaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $authDetails = Auth::user();
            $_release = Release::with('totalClick');
            if($authDetails->user_role == USER_ROLE_USER) {
                $_release->where('created_by', '=', $authDetails->id);
            }
            $release = $_release->get();
            $dt = Datatables::of($release);
            $dt->addIndexColumn(); // Add Index is call Index Column
            $dt->addColumn('action', function ($row) {
                $btn = '<div class="">';
                $btn .='<button class="btn btn-outline-theme dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="true" title="Status">Action</button>';
                $btn .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">';
                $btn .= '<li class="dropdown-item">';
                $btn .= '<a title="Edit" href="'.route("release.edit", ['id'=>$row->id]).'" class="btn btn-outline-success"><i class="fas fa-edit"></i> Edit</a>';
                $btn .= '</li>';
                $btn .= '<li class="dropdown-item">';
                $btn .= '<form method="post" action="'.route('release.delete', ['id'=>$row->id]).'">'.csrf_field().' '.method_field("DELETE").'</form>
                                <a title="Delete" href="javascript:;" data-url="" class="btn btn-outline-danger release-delete-link">
                                    <i class="fas fa-trash"></i> Delete
                                </a>';
                $btn .= '</li>';
                $btn .= '<li  class="dropdown-item">
                                <a href="'.route('release.landing-page', ['key'=>$row->slug]).'" class="btn btn-outline-primary" title="Landing Page" target="_blank">
                                    <i class="fas fa-eye"></i> Landing Page
                                </a>
                            </li>';
                $btn .= '<li  class="dropdown-item">
                                <a data-url="'.route('release.landing-page', ['key'=>$row->slug]).'" title="Copy Short URL" href="javascript:;" class="btn btn-outline-lime copy-btn">
                                    <i class="fas fa-copy"></i> Copy Short URL
                                </a>
                            </li>';
                $btn .= '<li  class="dropdown-item">
                        <a title="Statistics" href="'.route('release.statistics', ['id'=>$row->id]).'" class="btn btn-outline-info">
                            <i class="fa fa-chart-bar"></i> Statistics
                        </a>
                    </li>';
                $btn .= '</ul>';
                $btn .= '</div>';
                return $btn;
            })->editColumn('created_at', function ($create) {
                return $create->created_at != null ? $create->created_at->format('F d,Y') : "-";
            })
            ->editColumn('total_click', function ($total_click) {
                return !empty($total_click->totalClick) ? $total_click->totalClick->count() : 0;
            })
            ->addColumn('cover', function ($cover) {
                $coverPath = $cover->cover;
                return $coverPath;
            })
            ->editColumn('release', function ($release) {
                return $release->release_date != null ? date('M d,Y', strtotime($release->release_date)) : "-";
            });
            return $dt->make(true);
        }
        return view('pages.release.index');
    }
    public function show()
    {
        $authDetails = Auth::user();
        $musicLabels = Store::where('user_id', '=', $authDetails->id)->get();
        return view('pages.release.form', ['release'=>[],'musicLabels'=>$musicLabels]);
    }
    public function save(Request $request)
    {
        $todayDate = date('m/d/Y');
        $authDetails = Auth::user();
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        $rules = [
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:'.$max,
            'audio_preview' => 'nullable|mimes:mp3,wav|max:'.$max,
            'track' => 'required',
            'artist' => 'required',
            'label' =>  'required',
            // 'release_date' =>  'required|date',
            'platformCode.*.code' => 'required',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'spotify_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'soundcloud_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'web_url' => 'nullable|url',
        ];

        if($request->coverType == "URL") {
            $rules['cover_url'] = 'required|url';
            unset($rules['cover']);
        }
        if(isset($request->track_type)) {
            foreach($request->track_type as $key => $trackType) {
                if($trackType['track_type'] == 'track_id') {
                    $rules['platformUrl.'.$key.'.id'] = 'required';
                    unset($rules['platformUrl.'.$key.'.urls']);
                } else {
                    $rules['platformUrl.'.$key.'.urls'] = 'required|url';
                }
            }
        }

        $messages = [
            'cover.required' => 'Cover is required',
            'cover.image' => 'Cover should be image',
            'cover.mimes' => 'Cover extension must be .jped, .png, .jpg',
            'artist.required' => 'Artist is reqiured',
            'track.required' => 'Track is reqiured',
            'label.required' => "Label is required",
            // 'release_date.required' => "Release date is required",
            // 'release_date.date' => "Release date must be date",
            'platformCode.*.code.required' => 'Platform is required',
            'platformUrl.*.urls.required' => 'Platform URL is required',
            'platformUrl.*.urls.url' => 'Invalid Platform URL',
            'platformUrl.*.id.required' => 'Platform ID is required',
            'audio_preview.mimes' => 'Audio preview extension must be .mp3 or .wav',
            'facebook_url.url' => 'Facebook URL must be valid',
            'twitter_url.url' =>  'Twitter URL must be valid',
            'youtube_url.url' =>  'Youtube URL must be valid',
            'spotify_url.url' =>  'Spotify URL must be valid',
            'instagram_url.url' =>  'Intagram URL must be valid',
            'soundcloud_url.url' =>  'Soundcloud URL must be valid',
            'tiktok_url.url' =>   'Tiktok URL must be valid',
            'web_url.url' =>   'Website URL must be valid',
        ];

        $validatedData = $request->validate($rules, $messages);
        $release = new Release();
        if($request->coverType == "Image") {
            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $coverFileName = Str::random(20).".".$cover->getClientOriginalExtension();
                $release->cover= $coverFileName;
            }
        } else {
            $release->cover= $request->cover_url;
        }
        if ($request->hasFile('audio_preview')) {
            $audio_preview = $request->file('audio_preview');
            $audio_previewFileName = Str::random(20).".".$audio_preview->getClientOriginalExtension();
            $release->audio_preview = $audio_previewFileName;
        }

        $release->artist= $request->artist;
        $release->label = $request->label;
        $release->release_date = $request->release_date;
        $release->total_click = 0;
        $release->created_by = $authDetails->id;
        $release->track = $request->track;
        $release->slug = $this->slugify($request->track);
        $release->facebook_url = $request->facebook_url;
        $release->twitter_url = $request->twitter_url;
        $release->youtube_url = $request->youtube_url;
        $release->spotify_url = $request->spotify_url;
        $release->instagram_url = $request->instagram_url;
        $release->soundcloud_url = $request->soundcloud_url;
        $release->tiktok_url = $request->tiktok_url;
        $release->web_url = $request->web_url;
        $release->save();
        if($request->coverType == "Image") {
            uploadFileToStorage($cover, Release::$releasePath.$release->id."/".$coverFileName, null, null, null);

            Release::where('id', '=', $release->id)->update([
               'cover' => url('/').'/storage/'.Release::$releasePath.$release->id."/".$coverFileName,
           ]);
        }

        if ($request->hasFile('audio_preview')) {
            mp3FileToStorage($cover, Release::$releasePath.$release->id."/", $audio_previewFileName);
            Release::where('id', '=', $release->id)->update([
               'audio_preview' => url('/').'/storage/'.Release::$releasePath.$release->id."/".$audio_previewFileName,
           ]);
        }

        $platformCodes = $request->platformCode;
        $platformUrls = $request->platformUrl;
        foreach($platformCodes as $key => $platformCode) {
            $releasePlatform =  new ReleasePlatform();
            $releasePlatform->release_id = $release->id;
            $releasePlatform->code = $platformCode['code'];
            $releasePlatform->url = explode("?", $platformUrls[$key]['urls'])[0];
            $releasePlatform->track_id = $platformUrls[$key]['id'];
            $releasePlatform->level_order = ($key +1);
            $releasePlatform->save();
        }
        return redirect()->route('releases')->with('status', 'Release Saved Sucessfully..!');
    }
    public function delete($id)
    {
        $releaseClick = ReleaseClick::where('release_id', $id)->delete();
        $releasePlatform = ReleasePlatform::where('id', $id)->delete();
        $releasePath = Release::$releasePath.$id;
        removeDirectoryFromStorage($releasePath);
        Release::where('id', '=', $id)->delete();
        return  redirect()->route('releases')->with('status', 'Release Deleted Sucessfully..!');
    }

    public function deletePlatform(Request $request)
    {
        $releaseClick = ReleaseClick::where('platform_id', $request->id)->delete();
        $releasePlatform = ReleasePlatform::where('id', $request->id)->delete();
        return "Release platform deleted successfully";
    }

    public function edit($id)
    {
        $authDetails = Auth::user();
        $release = Release::with('platform')->where('id', $id)->first();
        if(empty($release)) {
            return redirect()->route('releases')->with('error', "Sorry Smartlink doesn't exit");
        }
        $releasePath = Release::$releasePath.$release->id;

        $musicLabels = Store::get();
        return view('pages.release.form', ['release'=>$release,'releasePath'=>$releasePath,'musicLabels'=>$musicLabels]);
    }
    public function update(Request $request)
    {
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        $rules = [
            'cover' => 'required|image|mimes:jpeg,png,jpg|max:'.$max,
            'audio_preview' => 'nullable|mimes:mp3,wav|max:'.$max,
            'track' => 'required',
            'artist' => 'required',
            'label' =>  'required',
            // 'release_date' =>  'required|date',
            'platformCode.*.code' => 'required',
            //'platformUrl.*.urls' => 'required|url',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'spotify_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'soundcloud_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'web_url' => 'nullable|url',
        ];

        if($request->coverType == "URL") {
            $rules['cover_url'] = 'required|url';
            unset($rules['cover']);
        }

        if($request->oldCover != '') {
            unset($rules['cover']);
        }

        if(isset($request->track_type)) {
            foreach($request->track_type as $key => $trackType) {

                if($trackType['track_type'] == 'track_id') {
                    $rules['platformUrl.'.$key.'.id'] = 'required';
                    unset($rules['platformUrl.'.$key.'.urls']);
                } else {
                    $rules['platformUrl.'.$key.'.urls'] = 'required|url';
                }
            }
        }
        $messages = [
            'cover.required' => 'Cover is required',
            'cover.image' => 'Cover should be image',
            'cover.mimes' => 'Cover extension must be .jped, .png, .jpg',
            'artist.required' => 'Artist is reqiured',
            'track.required' => 'Track is reqiured',
            'track.url' => 'Please enter valid url',
            'label.required' => "Label is required",
            // 'release_date.required' => "Release date is required",
            // 'release_date.date' => "Release date must be date",
            'platformCode.*.code.required' => 'Platform is required',
            'platformUrl.*.urls.required' => 'Platform URL is required',
            'platformUrl.*.urls.url' => 'Invalid Platform URL',
            'platformUrl.*.id.required' => 'Platform ID is required',
            'audio_preview.mimes' => 'Audio preview extension must be .mp3 or .wav',
            'facebook_url.url' => 'Facebook URL must be valid',
            'twitter_url.url' =>  'Twitter URL must be valid',
            'youtube_url.url' =>  'Youtube URL must be valid',
            'spotify_url.url' =>  'Spotify URL must be valid',
            'instagram_url.url' =>  'Intagram URL must be valid',
            'soundcloud_url.url' =>  'Soundcloud URL must be valid',
            'tiktok_url.url' =>   'Tiktok URL must be valid',
            'web_url.url' =>   'Website URL must be valid',
        ];

        $validatedData = $request->validate($rules, $messages);

        $cover = $request->file('cover');
        $authDetails = Auth::user();
        $releasePath = Release::$releasePath.$request->id.'/';
        if($request->coverType == "Image") {
            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $coverfilename = Str::random(20).".".$cover->getClientOriginalExtension();
                uploadFileToStorage($cover, $releasePath.$coverfilename, null, null, null);
                $cover= url('/').'/storage/'.Release::$releasePath.$request->id."/".$coverfilename;
                $removeOldCoverString = str_replace(url('/').'/storage/uploads/releases/'.$request->id.'/', "", $request->oldCover);
                removeFileFromStorage($releasePath.$removeOldCoverString);
            } else {
                $cover = $request->oldCover;
            }
        } else {
            $cover= $request->cover_url;
            $removeOldCoverString = str_replace(url('/').'/storage/uploads/releases/'.$request->id.'/', "", $request->oldCover);
            removeDirectoryFromStorage(Release::$releasePath.$request->id);
        }

        if ($request->hasFile('audio_preview')) {
            $audio_preview = $request->file('audio_preview');
            $audio_previewfilename = Str::random(20).".".$audio_preview->getClientOriginalExtension();
            mp3FileToStorage($audio_preview, Release::$releasePath.$request->id."/", $audio_previewfilename);
            $audio_preview= url('/').'/storage/'.Release::$releasePath.$request->id."/".$audio_previewfilename;
            $removeOldCoverString = str_replace(url('/').'/storage/uploads/releases/'.$request->id.'/', "", $request->old_audio_preview);
            removeFileFromStorage($releasePath.$removeOldCoverString);
        } else {
            $audio_preview = $request->old_audio_preview;
        }

        Release::where('id', '=', $request->id)->update([
            'cover' => $cover,
            'audio_preview' => $audio_preview,
            'artist' => $request->artist,
            'label' => $request->label,
            'release_date' => $request->release_date,
            'created_by' => $authDetails->id,
            'track' =>$request->track,
            'slug' =>  $this->slugify($request->track),
            'facebook_url' => $request->facebook_url,
            'twitter_url' => $request->twitter_url,
            'youtube_url' => $request->youtube_url,
            'spotify_url' => $request->spotify_url,
            'instagram_url' => $request->instagram_url,
            'soundcloud_url' => $request->soundcloud_url,
            'tiktok_url' => $request->tiktok_url,
            'web_url' => $request->web_url
        ]);

        $platformCodes = $request->platformCode;
        $platformUrls = $request->platformUrl;
        $platformId = $request->platformId;
        foreach($platformCodes as $key => $platformCode) {
            if(isset($platformId[$key]['id'])) {
                ReleasePlatform::where('id', '=', $platformId[$key]['id'])->update([
                    'code' => $platformCode['code'],
                    'url' => explode("?", $platformUrls[$key]['urls'])[0],
                    'track_id' => $platformUrls[$key]['id'],
                ]);
            } else {
                $releasePlatform =  new ReleasePlatform();
                $releasePlatform->release_id = $request->id;
                $releasePlatform->code = $platformCode['code'];
                $releasePlatform->url = explode("?", $platformUrls[$key]['urls'])[0];
                $releasePlatform->track_id = $platformUrls[$key]['id'];
                $releasePlatform->save();

            }
        }

        return redirect()->route('releases')->with('status', 'Release Updated Sucessfully..!');
    }

    public function levelOrder(Request $request)
    {
        $post_order = isset($request->levelOrder) ? $request->levelOrder : [];
        if(count($post_order)>0) {
            for($order_no= 0; $order_no < count($post_order); $order_no++) {
                ReleasePlatform::where('id', '=', $post_order[$order_no])->update([
                    'level_order' => ($order_no+1),
                ]);
            }
            return ['status'=> 'sorting done'];
        }
    }




    public function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $length = 4;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $shortUrl       = '';
        $index = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $shortUrl .= $characters[ mt_rand(0, $index) ];
        }

        return $shortUrl;
    }

    public function statistics($id)
    {

        $authDetails = Auth::user();
        $release = Release::find($id);
        if(empty($release)) {
            return redirect()->route('releases')->with('error', "Sorry release doesn't exit");
        }
        $releasePath = $release->cover;
        // Getting Campaign Clicks Details
        $releaseClicks = ReleaseClick::where('release_id', '=', $id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->orderBy('created_at', 'desc')
        ->get();

        $visitors = ReleaseClick::where('release_id', '=', $id)
        ->select('id', 'visitor_id')
        ->groupBy('visitor_id')
        ->get();

        // Getting Referers
        $_referers = ReleaseClick::selectRaw('referer, count(*) AS total')
        ->where('release_id', '=', $id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->whereNotNull('referer')
        ->groupBy('referer')
        ->orderBy('total', 'desc')
        ->get();

        if($_referers->count() > 16) {
            $index = 0;
            $referers = [];
            foreach ($_referers as $referer) {
                if($index < 15) {
                    $referers[$index]['referer'] = $referer->referer;
                    $referers[$index++]['total'] = $referer->total;
                } else {
                    if(empty($referers[$index])) {
                        $referers[$index]['total'] = 0;
                    }
                    $referers[$index]['referer'] = 'Others';
                    $referers[$index]['total'] += $referer->total;
                }
            }
        } else {
            $referers = $_referers->toArray();
        }

        // get devices
        $_devices = ReleaseClick::selectRaw('device, count(*) AS total')
            ->where('release_id', '=', $id)
            ->whereNotNull('device')
            // ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('device')
            ->orderBy('total', 'desc')
            ->get();

        $devices = [];
        foreach($_devices as $device) {
            $devices['name'][] = $device->device;
            $devices['data'][] = $device->total;
        }
        // get browsers
        $_browsers = ReleaseClick::selectRaw('browser_type, count(*) AS total')
            ->where('release_id', '=', $id)
            ->whereNotNull('browser_type')
            // ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('browser_type')
            ->orderBy('total', 'desc')
            ->get();

        $browsers = [];
        foreach($_browsers as $browser) {
            $browsers['browser'][] = $browser->browser_type;
            $browsers['total'][] = $browser->total;
        }
        // get platforms
        $_platforms = ReleaseClick::selectRaw('os, count(*) AS total')
            ->where('release_id', '=', $id)
            ->whereNotNull('os')
            // ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('os')
            ->orderBy('total', 'desc')
            ->get();

        $platforms = [];
        foreach($_platforms as $platform) {
            $platforms['os'][] = $platform->os;
            $platforms['total'][] = $platform->total;
        }
        // get Music Platform
        $_music_platforms = ReleaseClick::selectRaw('music_platform, count(*) AS total')
            ->where('release_id', '=', $id)
            ->whereNotNull('music_platform')
            // ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('music_platform')
            ->orderBy('total', 'desc')
            ->get();
        $music_platforms = [];
        foreach($_music_platforms as $music_platform) {
            $music_platforms['music_platform'][] = $music_platform->music_platform;
            $music_platforms['total'][] = $music_platform->total;
        }

        // month wise total clicks
        $_month_wise_total_clicks = ReleaseClick::selectRaw('count(*) as total, MONTH(created_at) month')
        ->where('release_id', '=', $id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();

        $months_wise_total = [];
        for($i = 1; $i <= 12; $i++) {
            if(!empty($_month_wise_total_clicks[$i])) {
                $months_wise_total[] = $_month_wise_total_clicks[$i];
            } else {
                $months_wise_total[] = 0;
            }
        }

        // month wise unique clicks...
        $months_wise_unique = [];
        $_month_wise_unique_clicks = ReleaseClick::where('is_first_click', 1)
        ->where('release_id', '=', $id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();

        for($i = 1; $i <= 12; $i++) {
            if(!empty($_month_wise_unique_clicks[$i])) {
                $months_wise_unique[] = $_month_wise_unique_clicks[$i];
            } else {
                $months_wise_unique[] = 0;
            }
        }

        $countries = [];
        $index = 0;
        $countryTable = [];
        $countryClicks = ReleaseClick::selectRaw('country, count(*) as total')
        ->where('release_id', '=', $id)
        ->whereNotNull('country')
        // ->whereMonth('created_at', Carbon::now()->month)
        ->groupBy('country')
        ->orderBy('total', 'desc')
        ->get();

        foreach ($countryClicks as $key => $click) {
            $_countries = Country::where('short_code', $click->country)->get();
            foreach ($_countries as $key => $country) {
                $countries[] = array(
                        'latLng' =>array($country->latitude,$country->longitude),
                        'name'=>$country->country,
                        'total' => $click->total
                    );
                if($index < 5) {
                    $countryTable[$index]['name'] = $country->country;
                    $countryTable[$index++]['total'] = $click->total;
                } else {
                    if(empty($countryTable[$index])) {
                        $countryTable[$index]['total'] = 0;
                    }
                    $countryTable[$index]['name'] = 'Others';
                    $countryTable[$index]['total'] += $click->total;
                }
            }
        }
        $view = [
            'releasePath' => $releasePath,
            'release' => $release,
            'releaseClicks' => $releaseClicks,
            'referers' =>$referers,
            'devices' =>$devices,
            'browsers' => $browsers,
            'platforms' => $platforms,
            'months_wise_total' => $months_wise_total,
            'months_wise_unique' => $months_wise_unique,
            'music_platforms' => $music_platforms,
            'countries' =>$countries,
            'countryTable' =>$countryTable,
            'visitors' =>$visitors
        ];
        return view('pages.release.statistics', $view);
        //dd($view);
    }

    public function dateFilter($id, Request $request)
    {
        $from = $request->startDate;
        $to = $request->endDate;
        $ranges = $request->range;
        $authDetails = Auth::user();
        $release = Release::find($id);
        if(empty($release)) {
            return redirect()->route('campaigns')->with('error', "Sorry release doesn't exit");
        }
        $releasePath = $release->cover;

        $releaseClicks = ReleaseClick::where('release_id', '=', $id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->orderBy('created_at', 'desc')
        ->get();

        $visitors = ReleaseClick::where('release_id', '=', $id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->select('id', 'visitor_id')
        ->groupBy('visitor_id')
        ->get();

        $_referers = ReleaseClick::selectRaw('referer, count(*) AS total')
        ->where('release_id', '=', $id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->whereNotNull('referer')
        ->groupBy('referer')
        ->orderBy('total', 'desc')
        ->get();

        if($_referers->count() > 16) {
            $index = 0;
            $referers = [];
            foreach ($_referers as $referer) {
                if($index < 15) {
                    $referers[$index]['referer'] = $referer->referer;
                    $referers[$index++]['total'] = $referer->total;
                } else {
                    if(empty($referers[$index])) {
                        $referers[$index]['total'] = 0;
                    }
                    $referers[$index]['referer'] = 'Others';
                    $referers[$index]['total'] += $referer->total;
                }
            }
        } else {
            $referers = $_referers->toArray();
        }

        // get devices
        $_devices = ReleaseClick::selectRaw('device, count(*) AS total')
        ->where('release_id', '=', $id)
        ->whereNotNull('device')
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->groupBy('device')
        ->orderBy('total', 'desc')
        ->get();


        $devices = [];
        foreach($_devices as $device) {
            $devices['name'][] = $device->device;
            $devices['data'][] = $device->total;
        }
        // get browsers
        $_browsers = ReleaseClick::selectRaw('browser_type, count(*) AS total')
            ->where('release_id', '=', $id)
            ->whereNotNull('browser_type')
            ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
            ->groupBy('browser_type')
            ->orderBy('total', 'desc')
            ->get();

        $browsers = [];
        foreach($_browsers as $browser) {
            $browsers['browser'][] = $browser->browser_type;
            $browsers['total'][] = $browser->total;
        }

        // get platforms
        $_platforms = ReleaseClick::selectRaw('os, count(*) AS total')
        ->where('release_id', '=', $id)
        ->whereNotNull('os')
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->groupBy('os')
        ->orderBy('total', 'desc')
        ->get();

        $platforms = [];
        foreach($_platforms as $platform) {
            $platforms['os'][] = $platform->os;
            $platforms['total'][] = $platform->total;
        }

        // get Music Platform
        $_music_platforms = ReleaseClick::selectRaw('music_platform, count(*) AS total')
            ->where('release_id', '=', $id)
            ->whereNotNull('music_platform')
            ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
            ->groupBy('music_platform')
            ->orderBy('total', 'desc')
            ->get();
        $music_platforms = [];
        foreach($_music_platforms as $music_platform) {
            $music_platforms['music_platform'][] = $music_platform->music_platform;
            $music_platforms['total'][] = $music_platform->total;
        }

        // month wise total clicks
        $_month_wise_total_clicks = ReleaseClick::selectRaw('count(*) as total, MONTH(created_at) month')
        ->where('release_id', '=', $id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();



        $months_wise_total = [];
        for($i = 1; $i <= 12; $i++) {
            if(!empty($_month_wise_total_clicks[$i])) {
                $months_wise_total[] = $_month_wise_total_clicks[$i];
            } else {
                $months_wise_total[] = 0;
            }
        }

        // month wise unique clicks...
        $months_wise_unique = [];
        $_month_wise_unique_clicks = ReleaseClick::where('is_first_click', 1)
        ->where('release_id', '=', $id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();


        for($i = 1; $i <= 12; $i++) {
            if(!empty($_month_wise_unique_clicks[$i])) {
                $months_wise_unique[] = $_month_wise_unique_clicks[$i];
            } else {
                $months_wise_unique[] = 0;
            }
        }
        $countries = [];
        $index = 0;
        $countryTable = [];
        $countryClicks = ReleaseClick::selectRaw('country, count(*) as total')
        ->where('release_id', '=', $id)
        ->whereNotNull('country')
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->groupBy('country')
        ->orderBy('total', 'desc')
        ->get();


        foreach ($countryClicks as $key => $click) {
            $_countries = Country::where('short_code', $click->country)->get();
            foreach ($_countries as $key => $country) {
                $countries[] = array(
                        'latLng' =>array($country->latitude,$country->longitude),
                        'name'=>$country->country,
                        'total' => $click->total
                    );
                if($index < 5) {
                    $countryTable[$index]['name'] = $country->country;
                    $countryTable[$index++]['total'] = $click->total;
                } else {
                    if(empty($countryTable[$index])) {
                        $countryTable[$index]['total'] = 0;
                    }
                    $countryTable[$index]['name'] = 'Others';
                    $countryTable[$index]['total'] += $click->total;
                }
            }
        }
        $view = [
            'releasePath' => $releasePath,
            'release' => $release,
            'releaseClicks' => $releaseClicks,
            'referers' =>$referers,
            'devices' =>$devices,
            'browsers' => $browsers,
            'platforms' => $platforms,
            'months_wise_total' => $months_wise_total,
            'months_wise_unique' => $months_wise_unique,
            'music_platforms' => $music_platforms,
            'countries' =>$countries,
            'countryTable' =>$countryTable,
            'visitors' =>$visitors,
            'from'=>$from,
            'to' =>$to
        ];
        return view('pages.release.statistics', $view);
    }
}
