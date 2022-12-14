<div class="audio">
    <audio src="/audio/sound_button-click.wav" class="game_audio audio_button-click"></audio>

    @php
        if(isset($audioData)) {
            foreach($audioData as $audio) {
                @endphp
                    <audio src="/audio/sound_{{ $audio }}.wav" class="game_audio audio_{{ $audio }}"></audio>
                @php
            }
        }
    @endphp
</div>