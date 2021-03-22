<?php

test("mbcmul() works correctly", function () {
    expect(
        bcmul(bcmul(bcmul(5.5, 1.223), 1.234), 1.222)
    )->toEqual(
        mbcmul(5.5, 1.223, 1.234, 1.222)
    );
});