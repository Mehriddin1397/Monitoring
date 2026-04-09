@forelse($tasks as $task)
    @php
        $deadline = \Carbon\Carbon::parse($task->end_date);
        $daysLeft = \Carbon\Carbon::today()->diffInDays($deadline, false);
        $color = 'text-success';

        $today = $now->copy()->startOfDay();
        $endDate = $deadline->copy()->startOfDay();

        if ($daysLeft <= 3) $color = 'text-danger';
        elseif ($daysLeft <= 16) $color = 'text-warning';
    @endphp

    <tr class="single-item">
        <td>{{ $loop->iteration }}</td>
        <td>
            <h6 class="text-dark mb-0 text-break" style="white-space: normal; word-break: break-word; font-weight: normal;">
                {!! $task->title !!}
            </h6>
        </td>
        <td>
            @foreach($task->assignedUsers as $u)
                <span class="badge bg-primary">{{ $u->name }}</span> <br>
            @endforeach
        </td>
        <td>
            @foreach($task->categories as $category)
                {{ $category->name }} <br>
            @endforeach
        </td>
        <td>{{ \Carbon\Carbon::parse($task->start_date)->format('d-m-Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($task->end_date)->format('d-m-Y') }}</td>
        <td>
            @if ($task->status === 'bajarildi')
                <p class="text-success">Якунланди</p>
            @elseif ($now->greaterThan($endDate->copy()->endOfDay()) && $task->status !== 'bajarildi')
                <p class="{{ $color }}">Бажарилмади</p>
            @elseif ($endDate->isToday() && $task->status !== 'bajarildi')
                <p class="{{ $color }}">Топшириш куни</p>
            @else
                <p class="{{ $color }}">{{ $daysLeft }} - кун</p>
            @endif
        </td>
        <td>
            @if($task->status == 'yangi') Янги
            @elseif($task->status == 'bajarilmoqda') Бажарилмоқда
            @elseif($task->status == 'bajarildi') Бажарилди
            @else Узайтирилди
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center text-muted py-4">
            Hech qanday ma'lumot topilmadi...
        </td>
    </tr>
@endforelse
