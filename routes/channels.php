<?php

Broadcast::channel('chat', function ($user) {
    return auth()->check();
});
