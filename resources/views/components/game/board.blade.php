<div class="game-board active">
    @php
        for($i = 0; $i < 3; $i++) {
    @endphp
            <div class="row mt-2">
            @php
                for($j = 0; $j < 3; $j++) {
            @endphp
                    <div class="padding-1 col-4">
                        <div class="color-wrap tile-field" data-question-index="{{ ($i * 3) + $j }}">
                            <img src="/img/blank.jpg" class="img-fluid tile">
                        </div>
                    </div>
            @php
                }
            @endphp
            </div>
    @php
        }
    @endphp
</div>