<div class="wrapper-modal wrapper-modal_result">
    <div class="overlay overlay_result"></div>
    <div class="modal-window">
        <div class="result-animation-wrap">
            <h4 class="modal-header modal-header-result color-wrong">{{ __('Correct') }}</h4>
            <div class="result-wrapper">
                <div class="modal-wrap modal-wrap-result color-correct {{ $rtlClass }}">
                    <h4 class="modal-header question">{{ __('This question is not available in your langauge') }}</h4>
                    <div class="modal-message selected-answer-result">{{ __('Not available') }}</div>
                    <div class="modal-message result-correct-location active mt-1">{{ __('Not available') }}</div>
                </div>
            </div>
            <button class="btn btn-pos-action btn-result-continue mt-1">{{ __('Continue') }}</button>
        </div>
    </div>
</div>