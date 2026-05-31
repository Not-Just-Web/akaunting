<style>
    .akaunting-invoice-stamp {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 100vw;
        z-index: 10;
        font-size: 3rem;
        font-weight: bold;
        color: rgba(220, 38, 38, 0.18); /* red-600 with opacity */
        transform: translate(-50%, -50%) rotate(-20deg);
        pointer-events: none;
        user-select: none;
        text-align: center;
        white-space: nowrap;
    }
    .akaunting-invoice-stamp-paid {
        color: rgba(34,197,94,0.18); /* green-600 with opacity */
    }
    .akaunting-invoice-stamp-cancelled {
        color: rgba(220,38,38,0.18); /* red-600 with opacity */
    }
    .akaunting-invoice-stamp-draft {
        color: rgba(59,130,246,0.18); /* blue-600 with opacity */
    }
</style>
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
