<html>
   <body style="background-color:black; padding: 1px 0 40px 0">
      <div style=" max-width: 600px; margin: 0 auto; background: #313031; margin-top:40px; ">
         <div style=" padding: 32px 32px 0;">
            <div style="text-align: center">
               @if(isset($details['whiteImage']))
                  <img src="{{ $details['whiteImage'] }}" width="250" height="100">
               @endif
            </div>
            <div style="text-align: center; padding-top: 18px;">
               <span style="margin-bottom: 20px; font-family: helvetica,'sans-serifhelvetica neue'; font-size:18px; font-weight: bold; color: {{ $details['color'] }}; text-align: center ">
               {{ $details['campaign']->getTrack->first()->track }}
               </span>
            </div>
            <div style="text-align: center; padding-top: 18px; padding-bottom: 20px;">
               <p style="margin-bottom: 20px; font-family: 'Helvetica Neue',Arial; font-size:14px; font-weight: 400; color: #fff; text-align: center ">
                  {!! str_replace('white-space: pre;',"",$details['description']) !!}
               </p>
            </div>
            <div style="text-align: center;  padding-bottom: 20px;">
               <img src="{{ $details['path'] }}" style="  object-fit:contain; margin: 0 auto;">
            </div>
            <div style="text-align: center; padding-top: 40px; padding-bottom: 40px;">
               <a href="{{ $details['route'] }}" style="width: 100%; font-family: Helvetica,Arial; font-size: 16px; text-align: center; background: {{ $details['color'] }};padding: 11px 20px 11px 20px; margin-top:20px; border-radius: 3px; display: inline;" class="button" target="_blank" rel="noopener">
                  Click here to listen and download
               </a>
            </div>

            <div>
            <table align="left" border="0" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; float: none; margin: 0 auto">
                <tbody style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                   <tr style="display: flex; align-content: center; justify-content: space-between;">
                    <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border-collapse: collapse; table-layout: fixed; padding-right: 10px;" valign="top">
                        <a target="_blank" rel="noopener noreferrer" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4; max-width: 100%; word-wrap: break-word;" href="https://ct.klclick.com/f/a/j3Uq084RkjgnUcB1qRMOPw~~/AASl5QA~/RgRmCc7hP0RhaHR0cDovL3d3dy5mYWNlYm9vay5jb20vb3V0YnVyc3RyZWNvcmRzP19reD1KdVhPdjhZYzhjU19Kak1HR1A0R2lPRjZkekM0YUxEUlVlVDFzdjdwS1EwJTNELkhLTVNLSlcDc3BjQgpkIeFJJ2TTt4VKUhR0ZWtub0B0ZWtub2JlYXRzLmNvbVgEAAMKag~~" data-saferedirecturl="https://www.google.com/url?q=https://ct.klclick.com/f/a/j3Uq084RkjgnUcB1qRMOPw~~/AASl5QA~/RgRmCc7hP0RhaHR0cDovL3d3dy5mYWNlYm9vay5jb20vb3V0YnVyc3RyZWNvcmRzP19reD1KdVhPdjhZYzhjU19Kak1HR1A0R2lPRjZkekM0YUxEUlVlVDFzdjdwS1EwJTNELkhLTVNLSlcDc3BjQgpkIeFJJ2TTt4VKUhR0ZWtub0B0ZWtub2JlYXRzLmNvbVgEAAMKag~~&amp;source=gmail&amp;ust=1682508414558000&amp;usg=AOvVaw2eJktuVxTeryIecWGsU1lF"><img alt="Facebook" src="https://ci4.googleusercontent.com/proxy/Dp9eFft8NQ10Qip1ff-iAaoTbnmEi79K78wLatRkKFbo0YuFznCq_j_2vqMDVXnJWmInfUvVr-4xMTSWgQLbhk8TGfrRDUtvqfdPeSIcMOOBBYSyzP9RjjqwVf6VYC89bVrNkJZZBkrSqfM=s0-d-e1-ft#https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/subtleinverse/facebook_96.png" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border: 0; height: auto; line-height: 100%; max-width: 48px; outline: none; text-decoration: none; width: 48px; display: block;" width="48" class="CToWUd" data-bit="iit"></a>
                     </td>
                     <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border-collapse: collapse; table-layout: fixed; padding-right: 10px;" valign="top">
                         <a target="_blank" rel="noopener noreferrer" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4; max-width: 100%; word-wrap: break-word;" href="https://ct.klclick.com/f/a/60-WrL5yXSXgYRa5tOCztQ~~/AASl5QA~/RgRmCc7hP0RYaHR0cDovL3R3aXR0ZXIuY29tL291dGJ1cnN0cmVjP19reD1KdVhPdjhZYzhjU19Kak1HR1A0R2lPRjZkekM0YUxEUlVlVDFzdjdwS1EwJTNELkhLTVNLSlcDc3BjQgpkIeFJJ2TTt4VKUhR0ZWtub0B0ZWtub2JlYXRzLmNvbVgEAAMKag~~" data-saferedirecturl="https://www.google.com/url?q=https://ct.klclick.com/f/a/60-WrL5yXSXgYRa5tOCztQ~~/AASl5QA~/RgRmCc7hP0RYaHR0cDovL3R3aXR0ZXIuY29tL291dGJ1cnN0cmVjP19reD1KdVhPdjhZYzhjU19Kak1HR1A0R2lPRjZkekM0YUxEUlVlVDFzdjdwS1EwJTNELkhLTVNLSlcDc3BjQgpkIeFJJ2TTt4VKUhR0ZWtub0B0ZWtub2JlYXRzLmNvbVgEAAMKag~~&amp;source=gmail&amp;ust=1682508414558000&amp;usg=AOvVaw1ec56CKw7kBJFe5M3s2G-b"><img alt="Twitter" src="https://ci4.googleusercontent.com/proxy/zQ5cPde25ba-EqAqQ8KE-TsclVzSVv-lJ3GZDr6pklEFFL6LhOfXcHrXpBbCPTfRseDmfC9YyK66NlV7jaOLtuvntEpf3iYNR15asQxj5fYp6W0v9kTqfc81kedaJg4JQz7wXpIZZGL9aQ=s0-d-e1-ft#https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/subtleinverse/twitter_96.png" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border: 0; height: auto; line-height: 100%; max-width: 48px; outline: none; text-decoration: none; width: 48px; display: block;" width="48" class="CToWUd" data-bit="iit"></a>
                      </td>
                      <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border-collapse: collapse; table-layout: fixed; padding-right: 10px;" valign="top">
                         <a target="_blank" rel="noopener noreferrer" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4; max-width: 100%; word-wrap: break-word;" href="https://ct.klclick.com/f/a/CcLZnd13-d_C5Sa6eYpcYA~~/AASl5QA~/RgRmCc7hP0ReaHR0cDovL2luc3RhZ3JhbS5jb20vb3V0YnVyc3RyZWNvcmRzP19reD1KdVhPdjhZYzhjU19Kak1HR1A0R2lPRjZkekM0YUxEUlVlVDFzdjdwS1EwJTNELkhLTVNLSlcDc3BjQgpkIeFJJ2TTt4VKUhR0ZWtub0B0ZWtub2JlYXRzLmNvbVgEAAMKag~~" data-saferedirecturl="https://www.google.com/url?q=https://ct.klclick.com/f/a/CcLZnd13-d_C5Sa6eYpcYA~~/AASl5QA~/RgRmCc7hP0ReaHR0cDovL2luc3RhZ3JhbS5jb20vb3V0YnVyc3RyZWNvcmRzP19reD1KdVhPdjhZYzhjU19Kak1HR1A0R2lPRjZkekM0YUxEUlVlVDFzdjdwS1EwJTNELkhLTVNLSlcDc3BjQgpkIeFJJ2TTt4VKUhR0ZWtub0B0ZWtub2JlYXRzLmNvbVgEAAMKag~~&amp;source=gmail&amp;ust=1682508414558000&amp;usg=AOvVaw3uQA9oY41w90SEI-xu509L"><img alt="Instagram" src="https://ci4.googleusercontent.com/proxy/gfir70Ah-VmFGnVV6WnnDDFE8Am0cY4Dt0ecuOWTEK2V_PQqgp4uW1skd7108eBsPUQsGwYBBBFmIztTBkqWj32U2pyJcEuuxJrVxrbZ6MJs11s5g8sILXUS26g4qHs9rKJwqWPqgqE8YVL0=s0-d-e1-ft#https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/subtleinverse/instagram_96.png" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border: 0; height: auto; line-height: 100%; max-width: 48px; outline: none; text-decoration: none; width: 48px; display: block;" width="48" class="CToWUd" data-bit="iit"></a>
                      </td>
                      <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border-collapse: collapse; table-layout: fixed; padding-right: 10px;" valign="top">
                         <a target="_blank" rel="noopener noreferrer" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4; max-width: 100%; word-wrap: break-word;" href="https://ct.klclick.com/f/a/TJTF3_KVllXf-sx6AslQlQ~~/AASl5QA~/RgRmCc7hP0RxaHR0cDovL3d3dy55b3V0dWJlLmNvbS9jaGFubmVsL1VDUkc1NndtOU9kU2NIRHFueGw3OGF3Zz9fa3g9SnVYT3Y4WWM4Y1NfSmpNR0dQNEdpT0Y2ZHpDNGFMRFJVZVQxc3Y3cEtRMCUzRC5IS01TS0pXA3NwY0IKZCHhSSdk07eFSlIUdGVrbm9AdGVrbm9iZWF0cy5jb21YBAADCmo~" data-saferedirecturl="https://www.google.com/url?q=https://ct.klclick.com/f/a/TJTF3_KVllXf-sx6AslQlQ~~/AASl5QA~/RgRmCc7hP0RxaHR0cDovL3d3dy55b3V0dWJlLmNvbS9jaGFubmVsL1VDUkc1NndtOU9kU2NIRHFueGw3OGF3Zz9fa3g9SnVYT3Y4WWM4Y1NfSmpNR0dQNEdpT0Y2ZHpDNGFMRFJVZVQxc3Y3cEtRMCUzRC5IS01TS0pXA3NwY0IKZCHhSSdk07eFSlIUdGVrbm9AdGVrbm9iZWF0cy5jb21YBAADCmo~&amp;source=gmail&amp;ust=1682508414558000&amp;usg=AOvVaw02smWwKL25HS30yf7JTGrJ"><img alt="YouTube" src="https://ci5.googleusercontent.com/proxy/zL9mkUJxuOcKzyH7KvKRCrUTi29UEXnlh7oZFl_owix4vttCb-kE8dqHi8xMz7W0C9rSso17Iqbm8sL6yhrc3HEgn-j91HClCTjkV2l25HRIC5vXV1Q5JvN0U-eJ8yQsANsAv4Q3YqMXow=s0-d-e1-ft#https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/subtleinverse/youtube_96.png" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border: 0; height: auto; line-height: 100%; max-width: 48px; outline: none; text-decoration: none; width: 48px; display: block;" width="48" class="CToWUd" data-bit="iit"></a>
                      </td>
                      <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border-collapse: collapse; table-layout: fixed;" valign="top">
                         <a target="_blank" rel="noopener noreferrer" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3869d4; max-width: 100%; word-wrap: break-word;" href="https://ct.klclick.com/f/a/yrM2KjLHqYuKZ6y1r6lYeQ~~/AASl5QA~/RgRmCc7hP0R7aHR0cHM6Ly9wb2RjYXN0cy5hcHBsZS5jb20vZ2IvcG9kY2FzdC9vdXRidXJzdC1yYWRpby9pZDE1NjAyMDg3NjU_X2t4PUp1WE92OFljOGNTX0pqTUdHUDRHaU9GNmR6QzRhTERSVWVUMXN2N3BLUTAlM0QuSEtNU0tKVwNzcGNCCmQh4UknZNO3hUpSFHRla25vQHRla25vYmVhdHMuY29tWAQAAwpq" data-saferedirecturl="https://www.google.com/url?q=https://ct.klclick.com/f/a/yrM2KjLHqYuKZ6y1r6lYeQ~~/AASl5QA~/RgRmCc7hP0R7aHR0cHM6Ly9wb2RjYXN0cy5hcHBsZS5jb20vZ2IvcG9kY2FzdC9vdXRidXJzdC1yYWRpby9pZDE1NjAyMDg3NjU_X2t4PUp1WE92OFljOGNTX0pqTUdHUDRHaU9GNmR6QzRhTERSVWVUMXN2N3BLUTAlM0QuSEtNU0tKVwNzcGNCCmQh4UknZNO3hUpSFHRla25vQHRla25vYmVhdHMuY29tWAQAAwpq&amp;source=gmail&amp;ust=1682508414558000&amp;usg=AOvVaw0uF32gPVjrWu8UpbdLnTpb"><img alt="iTunes Podcast" src="https://ci5.googleusercontent.com/proxy/PQUeC84qdt5ybWJY1L712eovWtJncWBRNcmuc3CJzzUz1baJYntnIM2HwKvZhRtqDuxRj-edNFwTYLDgh7a794VuZtKEnQDWmoTcEPN46sbe8wlgEpDJEgPcA92YT5SCyjbNLpbdzOw=s0-d-e1-ft#https://d3k81ch9hvuctc.cloudfront.net/assets/email/buttons/subtleinverse/apple_96.png" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; border: 0; height: auto; line-height: 100%; max-width: 48px; outline: none; text-decoration: none; width: 48px; display: block;" width="48" class="CToWUd" data-bit="iit"></a>
                      </td>
                   </tr>
                </tbody>
             </table>
            </div>

         </div>
         <div style="font-size:12px; padding-top: 20px; background: #313031; color: #fff; width: 100%; padding: 0 25px 25px 25px;">
            <div style=" margin-bottom:30px; text-align: center">
                <div style="margin-bottom:15px; width: 100%; display: block; align-items: center;"></div>
                <div style="display: block; width: 100%; align-items: center;">
                  Delivered byPromoDrop for {{ $details['campaign']->label }} <br>
                  You can email {{ $details['campaign']->label }} at <a
                        href="mailto:{{ $details['user']->email }}" style="color: {{ $details['color'] }};">{{ $details['user']->email }}</a></div>
            </div>
            <div style="display: flex; width: 100%; justify-content: space-between;align-items: center; text-align: center;">
                <div style="width: 50%">
                    Question?<br> Need help?<br> Contact us at <a href="mailto:help@promodrop.me" style="color: {{ $details['color'] }};">help@promodrop.me</a>
                </div>
                <div style="width: 50%">
                    Our mailing address is:<br>
                    The Mount<br>
                    2 Woodstock Link<br>
                    Belfast, BT6 8DD<br>
                    <a style="color: {{ $details['color'] }};" href="{{ $details['unsubscription'] }}" target="_blank">Unsubscribe</a>
                </div>
            </div>
        </div>

         <div style="display: flex; justify-content: center; width: 100%; padding: 20px; background:{{ $details['color'] }}; color: #000; font-size: 16px;">
            <div class="bottom-paragraph-block" style="width: 100%; font-size: 16px; text-align: center;">
               
               <div class="bottom-paragraph-block" style="width: 100%; text-align: center;">
                  <div class="bottom-logo-block" style="font-size:16px; font-weight: bold; color: #fff;">
                     PromoDrop
                  </div>
                  <p style="font-size:12px; color: #000;  text-align: center;">
                      Do you need a promotion tool like this? Visit <a style="text-decoration: none; color: #fff"
                          href="promodrop.me">promodrop.me</a> for more information.
                      This promo was delivered by PromoDrop Limited for Label {{ $details['campaign']->label }}
                  </p>
              </div>
            </div>
         </div>
      </div>
   </body>
</html>