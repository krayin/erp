<style>
    .invoice-container {
        width: 350px;
        background-color: white;
        padding: 20px;
        border-radius: 12px;
    }

    :is(.dark .invoice-container) {
        background-color: rgb(36 36 39);
        border: 1px solid rgb(44 44 47);
    }

    .invoice-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
        color: #555;
    }

    :is(.dark .invoice-item) {
        color: #d1d5db;
    }

    .invoice-item span {
        font-weight: 600;
    }

    .paid {
        background-color: #eaffea;
        padding: 8px;
        border-radius: 5px;
    }

    .divider {
        border-bottom: 1px solid #ddd;
        margin: 12px 0;
    }

    :is(.dark .divider) {
        border-bottom-color: #374151;
    }

    .total {
        font-size: 16px;
        font-weight: bold;
        background-color: #f0f0f0;
        padding: 10px;
        border-radius: 5px;
    }

    :is(.dark .total) {
        background-color: rgba(255, 255, 255, 0.05);
        color: #f3f4f6;
    }

    .footer {
        text-align: center;
        font-size: 12px;
        color: #777;
        margin-top: 10px;
    }

    :is(.dark .footer) {
        color: #9ca3af;
    }
</style>

<div class="flex justify-end">
    <div class="invoice-container">
        <div class="invoice-item">
            <span>Subtotal</span>
            <span>8,951.00 USD</span>
        </div>
        <div class="invoice-item">
            <span>Tax (15%)</span>
            <span>1,342.65 USD</span>
        </div>
        <div class="invoice-item highlight">
            <span>Discount</span>
            <span>-250.00 USD</span>
        </div>
        <div class="divider"></div>

        <div class="invoice-item total">
            <span>Total Due</span>
            <span>5,043.65 USD</span>
        </div>

        <div class="footer">Due by: July 15, 2024</div>
    </div>
</div>
