<?php

// Base path otomatis (contoh: /BKPM/si-akademik6/public)
define('BASE_URL', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
