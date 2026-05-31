<style>
    .akaunting-invoice-stamp {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 20;
        width: 340px;
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.2rem;
        font-weight: 900;
        color: rgba(220, 38, 38, 0.18); /* red-600 with opacity */
        text-transform: uppercase;
        transform: translate(-50%, -50%) rotate(-15deg);
        pointer-events: none;
        user-select: none;
        text-align: center;
        border: 5px solid rgba(220,38,38,0.13);
        border-radius: 0.5rem;
        background: transparent;
        font-family: 'Impact', 'Arial Black', 'Courier New', Courier, 'Lucida Console', monospace, sans-serif;
        letter-spacing: 0.18em;
        text-shadow: 2px 2px 4px #00000022, 0 1px 0 #fff, 0 0 6px #00000011;
        box-shadow: none;
        mix-blend-mode: multiply;
        opacity: 0.85;
    }
    .akaunting-invoice-stamp-paid {
        color: rgba(34,197,94,0.18); /* green-600 with opacity */
        border-color: rgba(34,197,94,0.25);
    }
    .akaunting-invoice-stamp-cancelled {
        color: rgba(220,38,38,0.18); /* red-600 with opacity */
        border-color: rgba(220,38,38,0.25);
    }
    .akaunting-invoice-stamp-draft {
        color: rgba(59,130,246,0.18); /* blue-600 with opacity */
        border-color: rgba(59,130,246,0.25);
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
