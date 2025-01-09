<?php
    echo md5('awokwkwkkw');

    echo "<br>";

    echo sha1('123');

    echo "<br>";

    echo password_hash('123',PASSWORD_DEFAULT);

    echo "<br>";

    echo password_verify ('123','$2y$10$/UC7KmwaoI/xm1HMO4gRIeOrL9jKGLPqenLGnofJwIStIB9SB52jG');