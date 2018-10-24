@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            {{ trans('backpack::base.dashboard') }}<small>{{ trans('backpack::base.first_page_you_see') }}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="box-title">{{ trans('titles.statistics') }}</div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>PS Players</th>
                            <th>Xbox Players</th>
                            <th>Active Accounts</th>
                            <th>Todays Profit</th>
                            <th>Alltime Profit</th>
                            <th>Total Buys</th>
                            <th>Total Sales</th>
                            <th>Available Coins</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $ps_players->count() }}</td>
                            <td>{{ $xbox_players->count() }}</td>
                            <td>{{ $accounts->count() }}</td>
                            <td>{{ number_format(today_profit()) }}</td>
                            <td>{{ number_format(total_profit()) }}</td>
                            <td>{{ $buys->count() }}</td>
                            <td>{{ $sales->count() }}</td>
                            <td>{{ number_format($coins->sum('coins')) }}</td>
                            <td>{!! autobuyer_status(true) !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script>
        $(function () {

            function weeks_ago(date_object) {
                let newDate = date_object.setDate(date_object.getDate()-6);
                let date = new Date(newDate);
                return date.getDate();
            }

            let d = new Date();

            function createGraph(jsonObj) {
                $('#container').highcharts({
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Profit Graph'
                    },
                    subtitle: {
                        text: 'Data from the past week'
                    },
                    xAxis: {
                        type: 'datetime',
                        labels : {
                            formatter: function() {
                                var myDate = new Date(this.value);
                                var newDateMs = Date.UTC(myDate.getUTCFullYear(),myDate.getUTCMonth(),myDate.getUTCDate());
                                return Highcharts.dateFormat('%e. %b',newDateMs);
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Profit'
                        },
                        min: 0
                    },
                    tooltip: {
                        formatter: function() {
                            return '<span style="color:initial;">'+this.series.name +': '+ Highcharts.numberFormat(this.y,0);
                        }
                    },
                    series: [
                        {
                            name: 'Xbox One',
                            data: jsonObj.XBOX,
                            pointStart: Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), weeks_ago(new Date())),
                            pointInterval: 24 * 3600 * 1000,
                            color: '#55CCA2',
                        }, {
                            name: "PS4",
                            data: jsonObj.PS4,
                            pointStart: Date.UTC(d.getUTCFullYear(), d.getUTCMonth(), weeks_ago(new Date())),
                            pointInterval: 24 * 3600 * 1000,
                            color: '#3498db',
                        }
                    ]
                });
            }

            $.get("{{ route('dashboard.graphData') }}", function(response) {
                createGraph(response);
            });

        });
    </script>
@endsection