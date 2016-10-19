<?php

namespace App\Data;

class Constants
{
    const VERSION = '1.0.0.20160927';

    const REMEMBER_ME_COOKIE_LONGEVITY = 365; // days

    const DATE_FORMAT = 'm/d/Y';
    const DATE_FORMAT_JS = 'mm/dd/yyyy';
    const DATE_FORMAT_LABEL = 'mm/dd/yyyy';

    const CHECK_FILE_AVAILABILITY = true;

    const CURRENCY_SYMBOL = '$';

    const CONTEXT_PARAMETER = 'context';
    const CONTEXT_ADMIN = 'admin';

    const ARCHIVE_SHIPMENTS_ASSETS_COMMAND = 'app:archive-shipments-assets';
    const MIGRATE_SHIPMENTS_ASSETS_COMMAND = 'app:migrate-shipments-assets';
    const MIGRATE_PICKUP_REQUESTS_COMMAND  = 'app:migrate-pickuprequests';
    const MIGRATE_FILES_COMMAND = 'app:migrate-files';
    const PRUNE_ASSETS_COMMAND = 'app:prune-assets';

    const UPLOAD_DIRECTORY = '';
}