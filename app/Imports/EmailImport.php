<?php
namespace App\Imports;
use App\Models\EmailGroup;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class EmailImport implements ToModel{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row) {
        $authDetails = Auth::user();
        $emailGroup = EmailGroup::where('email', '=',$row[1])->exists();
        if($emailGroup){
            EmailGroup::where('email','=',$row[1])->update([
                'artist'     => $row[0],
                'email'    => $row[1],
                'group' => $row[2],
                'status'    => 1,
                'user_id' => $authDetails->id,
                'pass_key' => md5($row[1].$row[2])
            ]);
        }else{
            $email_group = new EmailGroup;
            $email_group->artist = $row[0];
            $email_group->email = $row[1];
            $email_group->group = $row[2];
            $email_group->status = 1;
            $email_group->user_id = $authDetails->id;
            $email_group->pass_key = md5($row[1].$row[2]);
            $email_group->save();
            // return new EmailGroup([
            //     'artist'     => $row[0],
            //     'email'    => $row[1],
            //     'group' => $row[2],
            //     'status'    => 1,
            //     'user_id' => $authDetails->id,
            //     'pass_key' => md5($row[1].$row[2])
            // ]);
        }
    }
}