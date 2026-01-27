<?php
function rupiah(int $n): string
{
    return 'Rp. ' . number_format($n, 0, ',', '.');
}