<div class="picker">
    <div class="wrap-in">
        <h3 class="color-picker__title title mb-4">
            {{ __('Tic Tac Toe') }}
        </h3>
        <label class="locale" hidden>{{ $locale }}</label>
        @if($data['isOnline'])
        <label class="room_number" hidden>{{ $data['room_number'] }}</label>
        <label class="isHost" hidden>{{ $data['isHost'] }}</label>
        @endif
        @php
            for ($i = 0; $i < $data['numOfPlayers']; $i++) {
        @endphp
        <span class="color-picker__title_2 player-{{ $data['playerNum'][$i] }} title user-profile-text">
            {{ $data['playerTitleText'][$i] }}
        </span>
            <div class="color-picker-wrap mt-4 mb-5 row flex-between color-picker-wrap_{{ $data['playerNum'][$i] }}">
                @php
                    for ($j = 0; $j < 5; $j++) {
                @endphp
                    <div class="color-wrap col-2 padding-0 color-img_{{ $data['colors'][$j] }} {{ $data['playersBtnClass'][$i] }}">
                        <img src="{{ asset('/img/'.$data['playerSymbol'][$i].'-'.$data['colors'][$j].'.png') }}" class="img-fluid color-img">
                    </div>
                @php
                    }
                @endphp
            </div>
        @php
            }
        @endphp
        <span class="user-profile-text">{{ __('Category') }}</span>
        <div class="mt-3 mb-4">
            @php
                $i = 0;
                $active = 'selected';
                do {
                    @endphp
                        <span class="btn question-category btn-pos-action {{ $active }}" data-category-id="{{ $data['categories_id'][$i] }}" data-category-name="{{ $data['categories'][$i] }}">{{ $data['categories'][$i] }}</span>
                    @php
                    $active = '';
                    $i++;
                } while ($i < count($data['categories']));

            @endphp
        </div>
        <button type="button" class="btn btn-pos-action btn-start control-btn-dis">{{ __('Start') }}</button>
        @if($data['isOnline'])
        <button type="button" class="btn btn-pos-action btn-ready control-btn-dis">{{ __('Ready') }}</button>
        @endif
    </div>
</div>