<?php

function delayFCMJob(): bool
{
    return (bool) setting('useFCMJob', "0");
}

function jobDelaySeconds(): int
{

    if ((bool) setting('useFCMJob', "0")) {
        return (int) setting('delayFCMJobSeconds', 5);
    }
    return 0;
}
