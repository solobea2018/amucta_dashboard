<?php


namespace Solobea\Dashboard\view;


class Components
{
    public static function errorToast($text, $duration=3000, $gravity='top', $position='right'){
        return <<<toast
<script>
                Toastify({
                    text: '{$text}',
                    backgroundColor: 'linear-gradient(to right, #ff416c, #ff4b2b)',
                    className: 'animate__animated animate__shakeX',
                    gravity: '{$gravity}',
                    position: '{$position}',
                    duration: {$duration}
                }).showToast();
            </script>
toast;

    }

}