@php($title = __('gdaemon_tasks.title_view'))

@extends('layouts.main')

@section('breadcrumbs')
    <g-breadcrumbs :items="[
        {'link':'/', 'text':'GameAP', 'icon': 'fas fa-home'},
        {'link':'{{ route("admin.gdaemon_tasks.index") }}', 'text':'{{ __("gdaemon_tasks.gdaemon_tasks") }}'},
        {'text':'{{ __("gdaemon_tasks.task") }}'},
    ]"></g-breadcrumbs>
@endsection

@section('content')
    @if ($gdaemonTask->status == 'waiting')
        {{ Form::open(['id' => 'form-destroy-' . $gdaemonTask->getKey(), 'url' => route('admin.gdaemon_tasks.cancel', $gdaemonTask->getKey()), 'style'=>'display:inline']) }}
            {{ Form::button( '<i class="fas fa-ban"></i>&nbsp;' . __('gdaemon_tasks.cancel'),
                [
                    'class' => 'btn btn-danger',
                    'v-on:click' => 'confirmAction($event, \'' . __('main.confirm_message'). '\')',
                    'type' => 'submit'
                ]
            ) }}
        {{ Form::close() }}
    @endif
    <hr>

    <div class="flex flex-wrap ">
        <div class="md:w-full pr-4 pl-4">
            <table class="stone-table mt-4">
                <tbody>
                    <tr class="stone-table-row">
                        <th class="px-2 py-2">{{ __('gdaemon_tasks.task') }}</th>
                        <td class="px-2 py-2">{!! $gdaemonTask->task !!}</td>
                    </tr>
                    <tr class="stone-table-row">
                        <th class="px-2 py-2">{{ __('gdaemon_tasks.status') }}</th>
                        <td class="px-2 py-2">
                            @if ($gdaemonTask->status == 'success')
                                <span class="label label-success">{{ $gdaemonTask->status }}</span>
                            @elseif($gdaemonTask->status == 'error')
                                <span class="label label-danger">{{ $gdaemonTask->status }}</span>
                            @elseif($gdaemonTask->status == 'waiting' || $gdaemonTask->status == 'working')
                                <span class="label label-warning">{{ $gdaemonTask->status }}</span>
                            @else
                                <span class="label label-default">{{ $gdaemonTask->status }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="stone-table-row">
                        <th class="px-2 py-2">{{ __('gdaemon_tasks.created') }}</th>
                        <td class="px-2 py-2">{!! \Gameap\Helpers\DateHelper::convertToLocal($gdaemonTask->created_at) !!}</td>
                    </tr>
                    <tr class="stone-table-row">
                        <th class="px-2 py-2">{{ __('gdaemon_tasks.updated') }}</th>
                        <td class="px-2 py-2">{!! \Gameap\Helpers\DateHelper::convertToLocal($gdaemonTask->updated_at) !!}</td>
                    </tr>
                </tbody>
            </table>
            
        </div>
    </div>
    
    <div class="flex flex-wrap ">
        <div class="md:w-full pr-4 pl-4">
            @if ($gdaemonTask->status == 'working')
                <task-output :task-id="{{ $gdaemonTask->id }}"></task-output>
            @else
                <pre class="console">{!! $gdaemonTask->output !!}</pre>
            @endif

        </div>
    </div>
@endsection
