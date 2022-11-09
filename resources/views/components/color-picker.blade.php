<div class="picker">
    <div class="wrap-in">
        <h3 class="color-picker__title title mb-4">
            {{ __('Tic Tac Toe') }}
        </h3>
        @php
            $numOfPlayers = 2;
            $colors = array("red", "green", "blue", "pink", "orange");
            $playerNum = array("one", "two");
            $playerTitleText = array(__('Player One'), __('Player Two'));
            $playerSymbol = array("x", "o");
            for ($i = 0; $i < $numOfPlayers; $i++) {
        @endphp
        <span class="color-picker__title_2 player-{{ $playerNum[$i] }} title user-profile-text">
            {{ $playerTitleText[$i] }}
        </span>
            <div class="color-picker-wrap mt-4 mb-5 row flex-between color-picker-wrap_{{ $playerNum[$i] }}">
                @php
                    for ($j = 0; $j < 5; $j++) {
                @endphp
                    <div class="color-wrap col-2 padding-0 color-img_{{ $colors[$j] }}">
                        <img src="{{ '/img/'.$playerSymbol[$i].'-'.$colors[$j].'.png' }}" class="img-fluid color-img">
                    </div>
                @php
                    }
                @endphp
            </div>
        @php
            }
        @endphp
        <button type="button" class="btn btn-start">
            {{ __('Start') }}
        </button>
    </div>
</div>