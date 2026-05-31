<style>
    .invoice-stamp {
        position: absolute;
        top: 40px;
        right: 40px;
        z-index: 10;
        font-size: 3rem;
        font-weight: bold;
        color: rgba(220, 38, 38, 0.18); /* red-600 with opacity */
        transform: rotate(-20deg);
        pointer-events: none;
        user-select: none;
    }
    .invoice-stamp-paid {
        color: rgba(34,197,94,0.18); /* green-600 with opacity */
    }
    .invoice-stamp-cancelled {
        color: rgba(220,38,38,0.18); /* red-600 with opacity */
    }
    .invoice-stamp-draft {
        color: rgba(59,130,246,0.18); /* blue-600 with opacity */
    }
</style>
@php
    // $showDraft: if set to false, do not show DRAFT watermark
    $showDraft = $showDraft ?? true;
@endphp
@if ($status === 'paid')
    <div class="invoice-stamp invoice-stamp-paid">PAID</div>
@elseif ($status === 'cancelled')
    <div class="invoice-stamp invoice-stamp-cancelled">CANCELLED</div>
@elseif ($status === 'draft' && $showDraft)
    <div class="invoice-stamp invoice-stamp-draft">DRAFT</div>
@endif
