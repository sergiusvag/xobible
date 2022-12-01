<div class="wrapper-modal wrapper-modal_round">
    <div class="overlay overlay_round"></div>
    <div class="modal-window">
        <div class="round-animation-wrap">
            <h4 class="modal-header">{{ __('Round № ')}}<span class="round-number"></span></h4>
            <div class="round-wrapper d-flex justify-content-between">
                <div class="modal-wrap wrap-host">
                    <h4 class="modal-header name-host">{{ __('Player One') }}</h4>
                    <div class="modal-message">{{ __('Correct : ') }}<span class="score-correct-round-host">0</span></div>
                    <div class="modal-message mt-1">{{ __('Bonus : ') }}<span class="score-bonus-round-host">0</span></div>
                    <div class="modal-message mt-1">{{ __('Wrong : ') }}<span class="score-wrong-round-host">0</span></div>
                    <div class="modal-message mt-3">{{ __('Total : ') }}<span class="score-total-round-host">0</span></div>
                </div>
                <div class="modal-wrap wrap-join">
                    <h4 class="modal-header name-join">{{ __('Player Two') }}</h4>
                    <div class="modal-message">{{ __('Correct : ') }}<span class="score-correct-round-join">0</span></div>
                    <div class="modal-message mt-1">{{ __('Bonus : ') }}<span class="score-bonus-round-join">0</span></div>
                    <div class="modal-message mt-1">{{ __('Wrong : ') }}<span class="score-wrong-round-join">0</span></div>
                    <div class="modal-message mt-3">{{ __('Total : ') }}<span class="score-total-round-join">0</span></div>
                </div>
            </div>
            <button class="btn btn-pos-action btn-round-continue mt-1">{{ __('Next Round') }}</button>
            <button class="btn btn-pos-action btn-round-finish mt-1">{{ __('Finish') }}</button>
        </div>
    </div>
</div>