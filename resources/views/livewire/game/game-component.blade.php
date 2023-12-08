<div class="container">
    <div class="row">
        <div class="col s12">
            <p>Credits: {{ $credits }}</p>
            <div class="row">
                @foreach($blocks as $block)
                    <div class="col s4 m4 l4">
                        <div class="card-panel center-align slot-block">{{ ucfirst($block[0]) }}</div>
                    </div>
                @endforeach
            </div>
            <button wire:click="roll" class="waves-effect waves-light btn" @if(!$buttonsVisible) disabled @endif>
                Roll
            </button>
            <button wire:click="cashOut" class="waves-effect waves-light btn red" @if(!$buttonsVisible) disabled @endif>
                Cash Out
            </button>
            <p>{{ $resultMessage }}</p>
        </div>
    </div>
</div>
