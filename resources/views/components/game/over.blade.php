<div class="wrapper-modal wrapper-modal_over">
    <div class="overlay overlay_over"></div>
    <div class="modal-window">
        <div class="over-animation-wrap">
            <h4 class="modal-header modal-header-over">{{ __('Game Over') }}</h4>
            <h4 class="modal-header modal-header-over">{{ __('Summary') }}</h4>
            <div class="over-wrapper d-flex">
                <div class="modal-wrap wrap-host">
                    <h4 class="modal-header name-host">{{ __('Sergey') }}</h4>
                    <div class="modal-message">{{ __('Correct : ') }}<span class="score-correct-game-host">0</span></div>
                    <div class="modal-message mt-1">{{ __('Bonus : ') }}<span class="score-bonus-game-host">0</span></div>
                    <div class="modal-message mt-1">{{ __('Wrong : ') }}<span class="score-wrong-game-host">0</span></div>
                    <div class="modal-message mt-3">{{ __('Total : ') }}<span class="score-total-game-host">0</span></div>
                </div>
                <div class="modal-wrap wrap-join">
                    <h4 class="modal-header name-join">{{ __('Admin') }}</h4>
                    <div class="modal-message">{{ __('Correct : ') }}<span class="score-correct-game-host">0</span></div>
                    <div class="modal-message mt-1">{{ __('Bonus : ') }}<span class="score-bonus-game-host">0</span></div>
                    <div class="modal-message mt-1">{{ __('Wrong : ') }}<span class="score-wrong-game-host">0</span></div>
                    <div class="modal-message mt-3">{{ __('Total : ') }}<span class="score-total-game-join">0</span></div>

                </div>
            </div>
            <button class="btn btn-pos-action btn-over-continue mt-1">{{ __('Continue') }}</button>
        </div>
    </div>
</div>