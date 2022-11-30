<div class="wrapper-modal wrapper-modal_question">
    <div class="overlay overlay_question"></div>
    <div class="modal-window">
        <div class="question-animation-wrap">
            <div class="question-wrapper">
                <div class="modal-wrap question-wrap {{ $rtlClass }}">
                    <div class="modal-header">{{ __('Number : ') }}<span class="question-number"></span></div>
                    <h4 class="modal-header question mt-2">{{ __('This question is not available in your langauge') }}</h4>
                    <div class="option mt-2">{{ __('Not available') }}</div>
                    <div class="option mt-2">{{ __('Not available') }}</div>
                    <div class="option mt-2">{{ __('Not available') }}</div>
                    <div class="option mt-2">{{ __('Not available') }}</div>
                </div>
            </div>
            <button class="btn btn-pos-action btn-question-confirm mt-1">{{ __('Confirm') }}</button>
        </div>
    </div>
</div>