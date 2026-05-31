@stack('add_new_button_start')

@if (! $hideCreate)
    @can($permissionCreate)
        <x-link href="{{ route($createRoute) }}" kind="primary" id="show-more-actions-new-{{ $document->type }}">
            {{ trans('general.title.new', ['type' => trans_choice($textPage, 1)]) }}
        </x-link>
    @endcan
@endif

@stack('edit_button_start')

@if (! in_array($document->status, $hideButtonStatuses))
    @if (! $hideEdit)
        @can($permissionUpdate)
            <x-link href="{{ route($editRoute, $document->id) }}" id="show-more-actions-edit-{{ $document->type }}">
                {{ trans('general.edit') }}
            </x-link>
        @endcan
    @endif
@endif

@stack('edit_button_end')

@can($permissionUpdate)
    @if (!in_array($document->status, ['paid', 'cancelled']))
        <form action="{{ route('invoices.cancel', $document->id) }}" method="POST" style="display:inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium leading-6 ml-2" onclick="return confirm('Are you sure you want to cancel this invoice?')">
                {{ __('Cancel Invoice') }}
            </button>
        </form>
    @endif
@endcan
