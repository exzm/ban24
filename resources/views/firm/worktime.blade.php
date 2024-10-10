<hr class="m-1">
<div class="worktime">
    <h3><i class="fal fa-clock"></i> Часы работы</h3>
    <table itemprop="openingHours" class="w-100 table mb-0 text-center">
        <thead class="thead-light">
        <tr>
            <th class="p-0">ПН</th>
            <th class="p-0">ВТ</th>
            <th class="p-0">СР</th>
            <th class="p-0">ЧТ</th>
            <th class="p-0">ПТ</th>
            <th class="p-0">СБ</th>
            <th class="p-0">ВС</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] AS $n => $week_day)
                @if(!empty($firm->worktime[$week_day]['working_hours']))
                    <td class="table-active p-1 align-middle {{ $loop->iteration == date('N') ? ($firm->isOpen($city->timezone) ? 'text-success font-weight-bold' : 'text-danger font-weight-bold') : 'text-muted' }} small">
                        @foreach($firm->worktime[$week_day]['working_hours'] AS $time)
                            <div>{{ $time['from'] }} - {{ $time['to'] }}</div>
                        @endforeach
                    </td>
                @else
                    <td class="table-danger p-1 text-muted">Выходной</td>
                @endif
            @endforeach
        </tr>
        </tbody>
    </table>
    <div class="p-2">
        Сейчас {{ $city->in() }} – {{ $city->time->format('H:i') }}, в это время «{{ $firm->name }}» {{ $firm->isOpen($city->timezone) ? '' : 'не'  }} работает.
        @if (!$firm->isOpen($city->timezone))
            @if ($firm->subtitleCases)
                @php($name = $firm->subtitleCases->caseMn(RU_IM) ?: "похожие компании")
            @else
                @php($name = "похожие компании")
            @endif
            <span class="d-block">
                Найти <a title="Открытые сейчас {{ $firm->groups->first()->name }} {{ $city->in() }}" class="font-weight-bold" href="{{ route('group', [$city->url, $firm->groups->first()->url]) }}#opennear">{{ $name }}</a> рядом с вами, которые сейчас работают.
            </span>
        @elseif ($firm->phones->count() > 0)
            <span class="d-block">
                Чтобы уточнить график работы позвоните по номеру <b>{{ $firm->phones->first()->text }}</b> <small class="text-muted">{{ $firm->phones->first()->comment }}</small>
            </span>
        @endif
    </div>
</div>
