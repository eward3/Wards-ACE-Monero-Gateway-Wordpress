<table class="striped" style="width:100%" cellspacing="0" cellpadding="5">
    <tr>
        <td>Exchange rate</td>
        <td>1 XMR = <?php echo $details['rate_formatted'].' '.$details['currency']; ?></td>
    </tr>
    <tr>
        <td>Total amount</td>
        <td><?php echo $details['amount_total_formatted']; ?> XMR</td>
    </tr>
    <tr>
        <td>Total paid</td>
        <td><?php echo $details['amount_paid_formatted']; ?> XMR</td>
    </tr>
    <tr>
        <td>Total due</td>
        <td><?php echo $details['amount_due_formatted']; ?> XMR</td>
    </tr>
    <tr>
        <td>Order age</td>
        <td><?php echo Monero_Gateway::format_seconds_to_time($details['order_age']) ?> ago</td>
    </tr>
    <tr>
        <td>Order exipires</td>
        <td>
            <?php echo $details['order_expires'] ?>
        </td>
    </tr>
    <tr>
        <td>Status</td>
        <td>
            <?php
                 switch($details['status']) {
                 case 'confirmed':
                     echo '<span style="color:#006400">Confirmed</span>';
                     break;
                 case 'paid':
                     echo '<span style="color:#006400">Paid, waiting confirmation</span>';
                     break;
                 case 'partial':
                     echo '<span style="color:#ffae42">Partial payment made</span>';
                     break;
                 case 'unpaid':
                     echo '<span style="color:#ffae42">Pending payment</span>';
                     break;
                 case 'expired_partial':
                     echo '<span style="color:#dc143c">Expired, partial payment made</span>';
                     break;
                 case 'expired':
                     echo '<span style="color:#dc143c">Expired</span>';
                     break;
                 }
                 ?>
        </td>
    </tr>
    <tr>
        <td>Payment id</td>
        <td><?php echo $details['payment_id'] ?></td>
    </tr>
    <tr>
        <td>Integrated address</td>
        <td style="word-break: break-all;"><?php echo $details['integrated_address'] ?></td>
    </tr>
</table>

<?php if(count($details['txs'])): ?>
<table class="striped" style="width:100%" cellspacing="0" cellpadding="5">
    <tr>
        <td>Transactions</td>
        <td>Height</td>
        <td>Amount</td>
    </tr>
    <?php foreach($details['txs'] as $tx): ?>
    <tr>
        <td>
            <a href="<?php echo MONERO_GATEWAY_EXPLORER_URL.'tx/'.$tx['txid']; ?>" target="_blank"><?php echo $tx['txid']; ?></a>
        </td>
        <td><?php echo $tx['height']; ?></td>
        <td><?php echo sprintf(MONERO_GATEWAY_ATOMIC_UNITS_SPRINTF, $tx['amount'] / MONERO_GATEWAY_ATOMIC_UNITS_POW); ?> XMR</td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if($details['status'] !== 'confirmed'): ?>
<div style="margin-top:15px; padding:12px; background:#f8f8f8; border:1px solid #ddd; border-radius:4px;">
    <h4 style="margin-top:0;">Manually Confirm Payment</h4>
    <p style="color:#666; font-size:12px;">Verify the payment on a block explorer, then confirm it here. Optionally paste the transaction ID.</p>
    <p>
        <label for="monero_manual_txid"><strong>Transaction ID (optional):</strong></label><br>
        <input type="text" id="monero_manual_txid" style="width:100%; margin-top:4px;" placeholder="e.g. 8a3b5f...">
    </p>
    <button type="button" id="monero_confirm_payment_btn" class="button button-primary" style="margin-top:5px;">
        Confirm Payment Received
    </button>
    <span id="monero_confirm_status" style="margin-left:10px;"></span>
</div>
<script>
jQuery(function($) {
    $('#monero_confirm_payment_btn').on('click', function() {
        if(!confirm('Are you sure this Monero payment has been received? This will update the order status.')) return;
        var btn = $(this);
        btn.prop('disabled', true);
        $('#monero_confirm_status').text('Processing...');
        $.post(ajaxurl, {
            action: 'monero_confirm_payment',
            order_id: <?php echo absint($details['order_id']); ?>,
            txid: $('#monero_manual_txid').val(),
            nonce: '<?php echo wp_create_nonce('monero_confirm_payment'); ?>'
        }, function(response) {
            if(response.success) {
                $('#monero_confirm_status').html('<span style="color:green;">&#10004; Payment confirmed! Reloading...</span>');
                setTimeout(function(){ location.reload(); }, 1500);
            } else {
                $('#monero_confirm_status').html('<span style="color:red;">Error: ' + response.data + '</span>');
                btn.prop('disabled', false);
            }
        }).fail(function() {
            $('#monero_confirm_status').html('<span style="color:red;">Request failed</span>');
            btn.prop('disabled', false);
        });
    });
});
</script>
<?php endif; ?>
