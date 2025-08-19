<?php

it('returns empty response for health check', function () {
    $this->get('/healthz')->assertNoContent();
});
