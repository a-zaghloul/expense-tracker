<x-layout>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div id="chart-container">
                        {!! $linechart->container() !!}
                        {!! $linechart->script() !!}
                    </div>
                    <br><br><br><br> <hr>
                    <div id="chart-container">
                        {!! $piechart->container() !!}
                        {!! $piechart->script() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout>
