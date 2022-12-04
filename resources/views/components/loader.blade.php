<div class="wrapper-modal wrapper-modal_loader">
    <div class="overlay overlay_loader"></div>
    <div class="modal-window modal-window-loader">
        <div class="modal-content align-items-center text-center">
            <div class="animation animation_5 ">
                <div class="block-wrap block-wrap_animation_5">
                    <div class="road">
                        <div class="driver">

                        </div>
                    </div>
                    <div class="road road-2">
                        <div class="driver">

                        </div>
                    </div>
                    <div class="road road-3">
                        <div class="driver">

                        </div>
                    </div>
                    <div class="road road-4">
                        <div class="driver">

                        </div>
                    </div>
                    <div class="road road-5">
                        <div class="driver">

                        </div>
                    </div>
                </div>
            </div>
            <div class="attempting-connection mt-3">{{ __('Connecting...') }} <span class="connection-counter"></span></div>
            <a href="{{ '/welcome/' . app()->getLocale() }}" class="btn btn-room-back mt-3 btn-room-back-loader hide">{{ __('Back') }}</a>
        </div>
    </div>
</div>