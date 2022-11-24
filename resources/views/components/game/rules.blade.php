<div class="wrapper-modal wrapper-modal_rules">
    <div class="overlay overlay_rules"></div>
    <div class="modal-window">
        <div class="rules-animation-wrap">
            <h4 class="modal-header">{{ __('Rules') }}</h4>
            <div class="modal-wrap rules-wrap active">
                <h4 class="modal-header">{{ __('Fields') }}</h4>
                <div class="modal-message-wrap">
                    <div class="modal-message">{{ __('The game with begin with usual 3 x 3 Tic Tac Toe field') }}</div>
                    <div class="modal-message">{{ __('Each field holds a question') }}</div>
                </div>
            </div>
            <div class="modal-wrap rules-wrap">
                <h4 class="modal-header">{{ __('Answers') }}</h4>
                <div class="modal-message-wrap">
                    <div class="modal-message">{{ __('If your answer is correct, the field is yours') }}</div>
                    <div class="modal-message">{{ __('If your answer is wrong, the field stays free (The question will still be the same on that field)') }}</div>
                </div>
            </div>
            <div class="modal-wrap rules-wrap">
                <h4 class="modal-header">{{ __('Score') }}</h4>
                <div class="modal-message-wrap">
                    <div class="modal-message">{{ __('For each correct answer you recieve a point') }}</div>
                    <div class="modal-message">{{ __('If you answer 3 questions in a row, you will recieve a bonus point') }}</div>
                    <div class="modal-message">{{ __('You cannot lose points') }}</div>
                </div>
            </div>
            <div class="modal-wrap rules-wrap">
                <h4 class="modal-header">{{ __('Rounds') }}</h4>
                <div class="modal-message-wrap">
                    <div class="modal-message">{{ __('Round ends only when all 9 questions answered correctly') }}</div>
                    <div class="modal-message">{{ __('You can finish the game in the end of each round') }}</div>
                </div>
            </div>
            <button class="btn btn-pos-action btn-rules-continue mt-1">{{ __('Continue') }}</button>
            <button class="btn btn-rules-close mt-1">{{ __('Close') }}</button>
        </div>
    </div>
</div>