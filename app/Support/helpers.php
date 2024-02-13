<?php

function obfuscateEmail(?string $email = null): string
{
    if (is_null($email)) {
        return '';
    }

    $split = explode("@", $email);

    if(sizeof($split) !== 2) {
        return '';
    }


    $firstPart       = $split[0];
    $qty             = (int) floor(strlen($firstPart) * 0.75);
    $remaining       = strlen($firstPart) - $qty;
    $maskedFirstPart = substr($firstPart, 0, $remaining) . str_repeat('*', $qty);

    $secondPart       = $split[1];
    $qty              = (int) floor(strlen($secondPart) * 0.75);
    $remaining        = strlen($secondPart) - $qty;
    $maskedSecondPart = str_repeat('*', $qty) . substr($secondPart, $remaining * -1, $remaining);

    return $maskedFirstPart . '@' . $maskedSecondPart;
}

function user(): ?\App\Models\User
{
    return auth()->user();
}
