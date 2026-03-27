@props(['amount' => 0, 'decimals' => 2])
<span>{{ function_exists('setting') ? setting('pos.currency','PHP') : 'PHP' }} {{ number_format((float) $amount, (int) $decimals) }}</span>
