var wavesurfer;
$('#volume').bootstrapSlider();

$(document).ready(function() {
    wavesurfer = WaveSurfer.create({
        backend: 'MediaElement',
        container: '.wave-container',
        waveColor: app.color.theme, //'#a8b6bc',
        progressColor: app.color.white, //app.color.green,//'#4e9cff',
        height: 100,
    });
    wavesurfer.load($('.wave').attr('data-path'), $('.wave').attr('data-peak').split(','));
    wavesurfer.drawBuffer();

    $('#current-volume').text(wavesurfer.getVolume());   

    // $('.song-active').attr('style', 'color: #fff !important');

	$('.song-switch').on('click',function(){
		var url = $(this).data('song-url');
		var songName = $(this).data('song');
		var switch_peak = $(this).attr('data-peak-switch');
		
		$('.song-switch').removeClass('song-active');
        // .removeAttr('style', 'color: #fff !important');

		var styles = "color: #fff !important;";

		$(this).addClass('song-active').attr('style',styles);
		$('.wave-song-title').text(songName);

		if (url != '') {
            wavesurfer.pause();
            wavesurfer.load(url, switch_peak.split(','));
			$('#duration-total').text("00:00");
			if (wavesurfer.isPlaying()) {
				$('.custom-play-btn').removeClass('fa-play');
				$('.custom-play-btn').addClass('fa-pause');
			} else {
				$('.custom-play-btn').removeClass('fa-pause');
				$('.custom-play-btn').addClass('fa-play');
			}
		}
	});
    
	$('#play-pause').click(function() {
        if (wavesurfer.isPlaying()) {
            $('.custom-play-btn').removeClass('fa-play');
            $('.custom-play-btn').addClass('fa-pause');
        } else {
            $('.custom-play-btn').removeClass('fa-pause');
            $('.custom-play-btn').addClass('fa-play');
        }
    });

    wavesurfer.setVolume(1 / 10);

    $('#volume').change(function() {
        var volume = $(this).val();
        var vol = volume / 10;
        wavesurfer.setVolume(vol);
        $('#current-volume').text(volume);
    });

    $('#mute-unmute').click(function() {
        if (wavesurfer.getMute()) {
            $('#mute-unmute').addClass('fa-volume-down');
            $('#mute-unmute').removeClass('fa fa-volume-mute');
            wavesurfer.setMute(false)
        } else {
            $('#mute-unmute').removeClass('fa-volume-down');
            $('#mute-unmute').addClass('fa fa-volume-mute');
            wavesurfer.setMute(true)
        }
    });
    wavesurfer.on('ready', updateTimer)
    wavesurfer.on('audioprocess', updateTimer)
});

function updateTimer() {
    var currentTime = wavesurfer.getCurrentTime();
    var length = wavesurfer.getDuration();
    if (secondsToTimestamp(length) == secondsToTimestamp(currentTime)) {
        $('#play-pause').addClass('fa-play');
        $('#play-pause').removeClass('fa-pause');
    }
    var formattedTime = secondsToTimestamp(wavesurfer.getCurrentTime());
    if (length) {
        $('#duration-total').text(secondsToTimestamp(length));
    }
    $('#duration-remaining').text(formattedTime);
}

function secondsToTimestamp(seconds) {
    seconds = Math.floor(seconds);
    var h = Math.floor(seconds / 3600);
    var m = Math.floor((seconds - (h * 3600)) / 60);
    var s = seconds - (h * 3600) - (m * 60);
    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    return m + ':' + s;
}