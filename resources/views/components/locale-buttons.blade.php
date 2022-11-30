<div class="row align-items-center mb-5">
    <div class="col-5">
        <a href="{{ url('/') }}">
            <img class="logo" src="{{ url('img/logo-brand-small.svg') }}" type="image/svg+xml" alt="x-stream"/>
        </a>
    </div>
    <div class="col-7 text-end">
        @php
        $supportLanguages = config('app.available_locales');
        $locale = app()->getLocale();

        foreach($supportLanguages as $lang) {
            if($lang ===  $locale) {
                @endphp
                    <span class="btn btn-locale btn-dis">{{ strtoupper($lang) }}</span>
                @php
            }
            else {
                @endphp
                    <a href="{{ substr_replace(url()->current(), $lang, -2) }}" class="btn btn-locale">{{ strtoupper($lang) }}</a>
                @php
            }
        }
        @endphp
    </div>
</div>