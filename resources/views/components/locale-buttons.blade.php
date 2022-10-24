<div class="row">
    <div class="col mb-5">
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