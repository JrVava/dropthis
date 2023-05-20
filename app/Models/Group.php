<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    
    protected $fillable = [
        'name',
        'description',
        'created_by_id',
        'updated_by_id '
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function grpLink(){
    	return $this->hasMany(LinksGroup::class);
    }

    public function groupClick(){
        // foreach($this->grpLink as $group){
            
        // }

        // dd($this->grpLink[0]->link->click);
        $link_ids = LinksGroup::where(['group_id' => $this->id])->select('link_id');
        $total_clicks = Click::whereIn('links_id', $link_ids)->count();
        $uniqe_clicks = Click::whereIn('links_id', $link_ids)->where('is_first_click', 1)->count();

        return $uniqe_clicks." / ".$total_clicks;
    }

    public function groupUniqueClick(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id');

        $uniqe_clicks = Click::whereIn('links_id', $link_ids)
        ->where('is_first_click', 1)
        ->whereMonth('created_at', '=', date('m'))
        ->count();
        return $uniqe_clicks;
    }

    public function groupUniqueClickFilter($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id');

        $uniqe_clicks = Click::whereIn('links_id', $link_ids)
        ->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
        ->where('is_first_click', 1)
        ->count();
        return $uniqe_clicks;
    }

    public function groupTotalClick(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id');
        $total_clicks = Click::whereIn('links_id', $link_ids)
        ->whereMonth('created_at', '=', date('m'))
        ->count();
        return $total_clicks;
    }

    public function groupTotalClickFilter($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id');

        $total_clicks = Click::whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
        ->whereIn('links_id', $link_ids)
        ->count();
        return $total_clicks;
    }
    public function groupMonthWiseUniqueClick(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id')
        ->get();

        $_month_wise_unique_clicks = Click::whereIn('links_id',$link_ids)
        ->where('is_first_click', 1)
        ->whereMonth('created_at', '=', date('m'))
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();
        return $_month_wise_unique_clicks;
    }

    public function groupFilterMonthWiseUniqueClick($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $_month_wise_unique_clicks = Click::whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
        ->whereIn('links_id',$link_ids)
        ->where('is_first_click', 1)
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();
        return $_month_wise_unique_clicks;
    }

    public function groupMonthWiseTotalClick(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id')
        ->get();

        $_month_wise_total_clicks = Click::whereIn('links_id',$link_ids)
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->whereMonth('created_at', '=', date('m'))
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();
        return $_month_wise_total_clicks;
    }

    public function groupFilterMonthWiseTotalClick($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $_month_wise_total_clicks = Click::whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
        ->whereIn('links_id',$link_ids)
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();
        return $_month_wise_total_clicks;
    }

    public function countryClicks(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id')
        ->get();

        $countryClicks = Click::selectRaw('country, count(*) as total')
        ->whereMonth('created_at', '=', date('m'))
        ->whereIn('links_id', $link_ids)
        ->whereNotNull('country')
        ->groupBy('country')
        ->get();
        return $countryClicks;
    }

    public function countryFilterClicks($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $countryClicks = Click::selectRaw('country, count(*) as total')
        ->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
        ->whereIn('links_id', $link_ids)
        ->whereNotNull('country')
        ->groupBy('country')
        ->get();
        return $countryClicks;
    }

    public function groupsReferers(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id')
        ->get();

        $_referers = Click::selectRaw('referer, count(*) AS total')
        ->whereMonth('created_at', '=', date('m'))
        ->whereIn('links_id',$link_ids)
        ->whereNotNull('referer')
        ->groupBy('referer')
        ->orderBy('total', 'desc')
        ->get();
        return $_referers;
    }

    public function groupsFilterReferers($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $_referers = Click::selectRaw('referer, count(*) AS total')
        ->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
        ->whereIn('links_id',$link_ids)
        ->whereNotNull('referer')
        ->groupBy('referer')
        ->orderBy('total', 'desc')
        ->get();
        return $_referers;
    }

    public function groupLinksDevice(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id')
        ->get();

        $_devices = Click::selectRaw('device, count(*) AS total')
            ->whereMonth('created_at', '=', date('m'))
            ->whereIn('links_id',$link_ids)
            ->whereNotNull('device')
            ->groupBy('device')
            ->orderBy('total', 'desc')
            ->get();
        return $_devices;
    }

     public function groupLinksFilterDevice($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $_devices = Click::selectRaw('device, count(*) AS total')
            ->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
            ->whereIn('links_id',$link_ids)
            ->whereNotNull('device')
            ->groupBy('device')
            ->orderBy('total', 'desc')
            ->get();
        return $_devices;
    }

    public function groupLinksBrowser(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id')
        ->get();

        $_browsers = Click::selectRaw('browser_type, count(*) AS total')
            ->whereMonth('created_at', '=', date('m'))
            ->whereIn('links_id',$link_ids)
            ->whereNotNull('browser_type')
            ->groupBy('browser_type')
            ->orderBy('total', 'desc')
            ->get();
        return $_browsers;
    }

    public function groupLinksFilterBrowser($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $_browsers = Click::selectRaw('browser_type, count(*) AS total')
            ->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
            ->whereIn('links_id',$link_ids)
            ->whereNotNull('browser_type')
            ->groupBy('browser_type')
            ->orderBy('total', 'desc')
            ->get();
        return $_browsers;
    }

    public function groupLinksPlatforms(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id')
        ->get();

        $_platforms = Click::selectRaw('os, count(*) AS total')
            ->whereMonth('created_at', '=', date('m'))  
            ->whereIn('links_id',$link_ids)
            ->whereNotNull('os')
            ->groupBy('os')
            ->orderBy('total', 'desc')
            ->get();
        return $_platforms;
    }

    public function groupLinksFilterPlatforms($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $_platforms = Click::selectRaw('os, count(*) AS total')
            ->whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
            ->whereIn('links_id',$link_ids)
            ->whereNotNull('os')
            ->groupBy('os')
            ->orderBy('total', 'desc')
            ->get();
        return $_platforms;
    }

    public function groupLinksInfo(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();
        $links = Link::whereIn('id',$link_ids)->get();
        return $links;
    }

    public function groupLinksFilterInfo($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $links = Link::whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
        ->whereIn('id',$link_ids)
        ->get();
        return $links;
    }

    public function groupClicksDetails(){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->whereMonth('created_at', '=', date('m'))
        ->select('link_id')
        ->get();

        $clicksDetails = Click::whereIn('links_id', $link_ids)
        ->whereMonth('created_at', '=', date('m'))
        ->get();
        return $clicksDetails;
    }

    public function groupClicksFilterDetails($startDate = '',$endDate = ''){
        $link_ids = LinksGroup::where(['group_id' => $this->id])
        ->select('link_id')
        ->get();

        $clicksDetails = Click::whereBetween('created_at', [$startDate.' 00:00:00',$endDate.' 23:59:59'])
        ->whereIn('links_id', $link_ids)
        ->get();
        return $clicksDetails;
    }
}
