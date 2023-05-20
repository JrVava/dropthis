$('#volume').bootstrapSlider();

$('.rateit').bind('rated', function (e) {
    var ri = $(this);
    var value = ri.rateit('value');            
    $('#rating').val(value);
    //ri.rateit('readonly', true); // this will only allow you for one time only 
});

	var app_colors = [];
	$.each(app.color, function(name, color){
		if(!name.endsWith("Rgb") && !app_colors.includes(color)){
			app_colors.push(color);
		}
	});

	var $switches = $('[rel="song-switch"]');
	
	var wavesurfer = WaveSurfer.create({
		backend: 'MediaElement',
		container: '.wave-container',
		waveColor: app.color.green,//'#a8b6bc',
		progressColor: app.color.theme,//'#4e9cff',
		height:100,
	});

	wavesurfer.drawBuffer();

	function getDuration(src, cb) {
		var audio = new Audio();
		$(audio).on("loadedmetadata", function(){
			cb(audio.duration);
		});
		audio.src = src;
	}
	
	$('.song-active').attr('style','color: #fff !important');
	$switches.each( function() {
		$(this).on('click', function() {
			var url = $(this).data('song-url');
			var songName = $(this).data('song');
			var switch_peak = $(this).data('peak-switch');
			console.log(switch_peak);
			//console.log(switch_peak);
			$('.song-switch').removeClass('song-active').removeAttr('style','color: #fff !important');
			$(this).addClass('song-active').attr('style','color: #fff !important');
			$('.wave-song-title').text(songName);

			document.getElementById('progress').style.display = 'block';
			if(url != ''){
				wavesurfer.load(url,switch_peak);
				// audio is not load show progress bar
				wavesurfer.on('loading', function (percents) {		
					$('.progress-bar').attr("aria-valuenow", percents);
					$('.progressBar').text(percents+"%");
					$(".progress-bar").css("width", percents+"%");
				});
				// audio is load hide progress bar
				wavesurfer.on('ready', function () {
					document.getElementById('progress').style.display = 'none';
				});
				
				$('#duration-total').text("00:00");
				if(wavesurfer.isPlaying()){
					$('.custom-play-btn').removeClass('fa-play');
					$('.custom-play-btn').addClass('fa-pause');
				}else {
					$('.custom-play-btn').removeClass('fa-pause');
					$('.custom-play-btn').addClass('fa-play');
				}
			}
		});
	});
	wavesurfer.load($('.wave').attr('data-path'),$('.wave').attr('data-peak').split(','));
	wavesurfer.drawBuffer();
	
	//document.getElementById('progress').style.display = 'block';
	
	wavesurfer.on('loading', function (percents) {		
		$('.progress-bar').attr("aria-valuenow", percents);
		$('.progressBar').text(percents+"%");
		$(".progress-bar").css("width", percents+"%");
	});
	// audio is load hide progress bar
	wavesurfer.on('ready', function () {
		document.getElementById('progress').style.display = 'none';
		 wavesurfer.backend.peaks = $('.wave').attr('data-peak').split(',');
		
	});
	$('#current-volume').text(wavesurfer.getVolume());

	wavesurfer.on('ready', updateTimer)
	wavesurfer.on('audioprocess', updateTimer)

	function updateTimer() {
		var totalTime = wavesurfer.getDuration(),
		currentTime = wavesurfer.getCurrentTime(),
		remainingTime = totalTime - currentTime;
		var length  = wavesurfer.getDuration();
		//console.log(secondsToTimestamp(length),secondsToTimestamp(currentTime));
		if(secondsToTimestamp(length) == secondsToTimestamp(currentTime)){
			$('#play-pause').addClass('fa-play');
			$('#play-pause').removeClass('fa-pause');
		}
		var formattedTime = secondsToTimestamp(wavesurfer.getCurrentTime());
		if(length){
			$('#duration-total').text(secondsToTimestamp(length));
		}
		$('#duration-remaining').text(formattedTime);
	}

	function secondsToTimestamp(seconds) {
		seconds = Math.floor(seconds);
		var h = Math.floor(seconds / 3600);
		var m = Math.floor((seconds - (h * 3600)) / 60);
		var s = seconds - (h * 3600) - (m * 60);
		// h = h < 10 ? '0' + h : h;
		m = m < 10 ? '0' + m : m;
		s = s < 10 ? '0' + s : s;
		return m + ':' + s;
	}

	$(document).ready(function(){

		$('#play-pause').click(function(){
			if(wavesurfer.isPlaying()){
				$('.custom-play-btn').removeClass('fa-play');
				$('.custom-play-btn').addClass('fa-pause');
			}else {
				$('.custom-play-btn').removeClass('fa-pause');
				$('.custom-play-btn').addClass('fa-play');				
			}
		});

		wavesurfer.setVolume(1 / 10);

		$('#volume').change(function(){
			var volume = $(this).val();
			var vol = volume / 10;
			wavesurfer.setVolume(vol);
			wavesurfer.play();
			$('#current-volume').text(volume);
		});

		$('#mute-unmute').click(function(){
			if(wavesurfer.getMute()){
				$('#mute-unmute').addClass('fa-volume-down');
				$('#mute-unmute').removeClass('fa fa-volume-mute');
				wavesurfer.setMute(false)
			}else{
				$('#mute-unmute').removeClass('fa-volume-down');
				$('#mute-unmute').addClass('fa fa-volume-mute');
				wavesurfer.setMute(true)
			}
		});
	});