@php
    // $showDraft: if set to false, do not show DRAFT watermark
    $showDraft = $showDraft ?? true;
@endphp
@if ($status === 'paid')
    <div class="akaunting-invoice-stamp akaunting-invoice-stamp-paid">PAID</div>
@elseif ($status === 'cancelled')
    <div class="akaunting-invoice-stamp akaunting-invoice-stamp-cancelled">CANCELLED</div>
@elseif ($status === 'draft' && $showDraft)
    <div class="akaunting-invoice-stamp akaunting-invoice-stamp-draft">DRAFT</div>
@endif
